<?php

namespace Tests\Feature\Student\Note;

use App\Enums\Role;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiUrl = '/api/student/notes/';

    public function test_note_list()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        Note::factory(20)->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.note.list']);

        $response = $this->get($this->apiUrl);

        $response->assertJsonCount(10, 'data');
    }

    public function test_note_create()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $note = Note::factory()->state(['user_id' => $user->id])->make();

        Sanctum::actingAs($user, ['student.note.create']);

        $response = $this->postJson($this->apiUrl, $note->toArray());

        $response->assertStatus(201);
    }

    public function test_note_show()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $note = Note::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.note.show']);

        $response = $this->get($this->apiUrl.$note->id);

        $response->assertJsonFragment(['id' => $note->id]);
    }

    public function test_note_update()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $note = Note::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.note.update']);

        $response = $this->putJson($this->apiUrl.$note->id, $note->toArray());
        $response->assertStatus(200);
    }

    public function test_note_delete()
    {
        $user = User::factory()->state(['role' => Role::Student])->create();
        $note = Note::factory()->state(['user_id' => $user->id])->create();

        Sanctum::actingAs($user, ['student.note.delete']);

        $response = $this->delete($this->apiUrl.$note->id);

        $response->assertStatus(200);
    }
}
