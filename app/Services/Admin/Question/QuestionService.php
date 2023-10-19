<?php

namespace App\Services\Admin\Question;

use App\Models\Question;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QuestionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Question::class);
    }

    public function create(object $request): object
    {
        $data = $request;

        if ($request->hasFile('src')) {
            $path = $request->file('src')->store('questions');
            $data = $request->safe()->merge(['src' => $path]);
        }

        $options = [];

        if ($request->is_image_option) {
            foreach ($request->options as $key => $option) {
                $path = $option['src']->store('options');
                $options[$key]['src'] = $path;
                $options[$key]['is_correct'] = $option['is_correct'] ?? false;
            }
        } else {
            foreach ($request->options as $key => $option) {
                $options[$key]['description'] = $option['description'];
                $options[$key]['is_correct'] = $option['is_correct'] ?? false;
            }
        }

        $question = null;
        DB::transaction(function () use (&$question, $data, $options) {
            $question = $this->model::create($data->all());

            $question->options()->createMany($options);

        });

        return $question;
    }

    public function update(object $request, int $id, $where = []): object
    {
        $question = $this->model::findOrFail($id);

        $data = $request;

        if ($request->hasFile('src')) {
            $path = $request->file('src')->store('questions');
            $data = $request->safe()->merge(['src' => $path]);
        }

        $options = [];

        if ($request->is_image_option) {
            foreach ($request->options as $key => $option) {
                $path = $option['src']->store('options');
                $options[$key]['src'] = $path;
                $options[$key]['is_correct'] = $option['is_correct'] ?? false;
            }
        } else {
            foreach ($request->options as $key => $option) {
                $options[$key]['description'] = $option['description'];
                $options[$key]['is_correct'] = $option['is_correct'] ?? false;
            }
        }

        DB::transaction(function () use ($data, $question, $options) {
            $question->update($data->all());

            $question->options()->delete();

            $question->options()->createMany($options);
        });

        return $question;
    }

    public function destroy(int $id, array $where = []): mixed
    {
        $question = $this->model::where($where)->with(['options'])->findOrFail($id);

        if ($question->src) {
            Storage::delete($question->src);
        }

        if ($question->is_image_option) {
            foreach ($question->options as $option) {
                Log::info($option->src);
                Storage::delete($option->src);
            }
        }

        DB::transaction(function () use ($question) {
            $question->options()->delete();
            $question->delete();
        });

        return $question;
    }
}
