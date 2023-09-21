<?php

namespace App\Http\Controllers\API\Student\Note;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\Student\Note\StoreNoteRequest;
use App\Http\Requests\Student\Note\UpdateNoteRequest;
use App\Http\Resources\Student\Note\NoteResource;
use App\Services\Student\Note\NoteService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends ApiController
{
    private NoteService $noteService;

    public function __construct(NoteService $service)
    {
        $this->noteService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.note.list'),
            Response::HTTP_FORBIDDEN
        );

        $query = request()->query('query');

        if ($query) {
            return $this->successResponse($this->noteService->search($query, 10, ['user_id' => auth()->id()]));
        }

        return $this->successResponse($this->noteService->paginate([], ['user_id' => auth()->id()]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.note.create'),
            Response::HTTP_FORBIDDEN
        );

        $note = $this->noteService->create($request);

        return $this->successResponse($note, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $note): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.note.show'),
            Response::HTTP_FORBIDDEN
        );

        return $this->successResponse(new NoteResource($this->noteService->show($note, [], ['user_id' => auth()->id()])));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, int $note): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.note.update'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('update', $this->noteService->show($note));

        $note = $this->noteService->update($request, $note);

        return $this->successResponse($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $note): JsonResponse
    {
        abort_unless(auth()->user()->tokenCan('student.note.delete'),
            Response::HTTP_FORBIDDEN
        );

        $this->authorize('delete', $this->noteService->show($note));

        $note = $this->noteService->destroy($note);

        return $this->successResponse($note);
    }
}
