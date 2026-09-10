<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'status' => 'required|in:belum dimulai,dikerjakan,selesai',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
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
        ];
    }
}
