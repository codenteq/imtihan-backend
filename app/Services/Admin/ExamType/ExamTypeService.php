<?php

namespace App\Services\Admin\ExamType;

use App\Models\ExamType;
use App\Models\ExamTypeCategory;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Storage;

class ExamTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(ExamType::class);
    }

    public function create(object $request): object|array
    {
        $path = $request->file('src')->store('exam-types');

        $examType = $this->model::create([
            'name' => $request->name,
            'language_id' => $request->language_id,
            'description' => $request->description,
            'src' => $path,
        ]);

        $examType->questionCategories()->attach($request->question_categories);

        return $examType;
    }

    public function update(object $request, int $id, array $where = []): object
    {
        $examType = parent::show($id);

        if ($request->hasFile('src')) {
            $path = $request->file('src')->store('exam-types');
            $data = $request->safe()->merge(['src' => $path]);
        } else {
            $data = $request->safe();
        }

        $examType->update($data->all());

        $examType->questionCategories()->sync($request->question_categories);

        return $examType;
    }

    public function show(int $id, array $with = [], array $where = []): mixed
    {
        $examType = parent::show($id, $with, $where);
        $questionCategories = ExamTypeCategory::where('exam_type_id', $id)
            ->pluck('question_category_id');

        return $examType->setAttribute('question_categories', $questionCategories);
    }

    public function destroy(int $id, array $where = []): mixed
    {
        $examType = parent::show($id, $where);

        if (Storage::exists($examType->src)) {
            Storage::delete($examType->src);
        }

        $examType->questionCategories()->detach();
        $examType->delete();

        return $examType;
    }

    public function getCategories(int $exam_type): array
    {
        return ExamTypeCategory::where('exam_type_id', $exam_type)
            ->with('questionCategory')
            ->get()
            ->toArray();
    }
}
