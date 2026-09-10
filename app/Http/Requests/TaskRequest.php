<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'status' => 'required|in:belum dimulai,dikerjakan,selesai',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,7z,jpg,jpeg,png,webp,txt,csv',
            'remove_attachment' => 'nullable|boolean',
        ];
    }

    
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max' => 'Judul tugas maksimal 255 karakter.',
            'priority.required' => 'Prioritas tugas wajib dipilih.',
            'priority.in' => 'Prioritas yang dipilih tidak valid.',
            'status.required' => 'Status tugas wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'due_date.date' => 'Tanggal tenggat harus berupa tanggal yang valid.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'attachment.file' => 'Berkas lampiran harus berupa file yang valid.',
            'attachment.max' => 'Ukuran berkas lampiran maksimal 10 MB.',
            'attachment.mimes' => 'Format berkas harus berupa PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, JPG, PNG, WEBP, TXT, atau CSV.',
        ];
    }
}
