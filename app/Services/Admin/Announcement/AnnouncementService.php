<?php

namespace App\Services\Admin\Announcement;

use App\Models\Announcement;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Storage;

class AnnouncementService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Announcement::class);
    }

    /*
     * Store a newly created resource in storage.
     *
     * @param object $request
     */
    public function create(object $request): object
    {
        $path = $request->file('src')->store('announcements');
        $data = $request->safe()->merge(['src' => $path]);

        return $this->model::create($data->all());
    }

    /*
     * Update the specified resource in storage.
     *
     * @param object $request
     * @param int $id
     * @param array $where
     * @return object
     */
    public function update(object $request, int $id, array $where = []): object
    {
        $announcement = $this->model::findOrFail($id);

        if ($request->hasFile('src')) {
            if (Storage::exists($announcement->src)) {
                Storage::delete($announcement->src);
            }
            $path = $request->file('src')->store('announcements');
            $data = $request->safe()->merge(['src' => $path]);
        } else {
            $data = $request->safe();
        }

        $announcement->update($data->all());

        return $announcement;
    }

    /*
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param array $where
     * @return bool
     */
    public function destroy(int $id, array $where = []): object
    {
        $announcement = $this->model::findOrFail($id);

        if (Storage::exists($announcement->src)) {
            Storage::delete($announcement->src);
        }

        $announcement->delete();

        return $announcement;
    }
}
