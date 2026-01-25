<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserAttachmentController extends Controller
{
    /**
     * عرض مرفقات الموظف
     */
    public function index(User $user)
    {
        $attachments = $user->attachments()->orderBy('created_at', 'desc')->get();
        $attachmentTypes = [
            'id_card' => 'بطاقة هوية',
            'passport' => 'جواز سفر',
            'driver_license' => 'رخصة قيادة',
            'birth_certificate' => 'شهادة ميلاد',
            'profile_picture' => 'صورة شخصية',
            'contract' => 'العقد الوظيفي',
            'cv' => 'السيرة الذاتية',
            'certificate' => 'شهادات أكاديمية',
            'insurance' => 'وثائق التأمين',
            'bank_account' => 'بيانات الحساب البنكي',
            'other' => 'أخرى',
        ];

        return view('admin.attachments.index', compact('user', 'attachments', 'attachmentTypes'));
    }

    /**
     * رفع مرفق جديد
     */
    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'attachment_type' => 'required|in:id_card,passport,driver_license,birth_certificate,profile_picture,contract,cv,certificate,insurance,bank_account,other',
            'description' => 'nullable|string|max:500',
            'is_primary' => 'nullable|boolean',
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // رفع الملف
            $path = $file->storeAs("attachments/user_{$user->id}", $fileName, 'public');

            // حفظ بيانات المرفق في قاعدة البيانات
            $attachment = UserAttachment::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'attachment_type' => $validated['attachment_type'],
                'description' => $validated['description'],
                'is_primary' => $validated['is_primary'] ?? false,
            ]);

            // إذا تم تحديد كـ أساسي، امسح الأساسي السابق من نفس النوع
            if ($validated['is_primary'] ?? false) {
                $user->attachments()
                    ->where('attachment_type', $validated['attachment_type'])
                    ->where('id', '!=', $attachment->id)
                    ->update(['is_primary' => false]);
            }

            return redirect()->route('admin.attachments.index', $user)
                ->with('success', 'تم رفع المرفق بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage());
        }
    }

    /**
     * حذف مرفق
     */
    public function destroy(User $user, UserAttachment $attachment)
    {
        // التحقق من أن المرفق يتبع للموظف الصحيح
        if ($attachment->user_id !== $user->id) {
            abort(403);
        }

        try {
            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            // حذف السجل من قاعدة البيانات
            $attachment->delete();

            return redirect()->route('admin.attachments.index', $user)
                ->with('success', 'تم حذف المرفق بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الملف');
        }
    }

    /**
     * تحميل/تنزيل المرفق
     */
    public function download(User $user, UserAttachment $attachment)
    {
        // التحقق من أن المرفق يتبع للموظف الصحيح
        if ($attachment->user_id !== $user->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    /**
     * تحديث حالة "الأساسي" للمرفق
     */
    public function setPrimary(User $user, UserAttachment $attachment)
    {
        // التحقق من أن المرفق يتبع للموظف الصحيح
        if ($attachment->user_id !== $user->id) {
            abort(403);
        }

        // امسح الأساسي السابق من نفس النوع
        $user->attachments()
            ->where('attachment_type', $attachment->attachment_type)
            ->update(['is_primary' => false]);

        // اجعل هذا المرفق أساسي
        $attachment->update(['is_primary' => true]);

        return redirect()->route('admin.attachments.index', $user)
            ->with('success', 'تم تحديث المرفق الأساسي');
    }
}
