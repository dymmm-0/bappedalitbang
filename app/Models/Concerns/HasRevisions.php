<?php

namespace App\Models\Concerns;

use App\Models\Revision;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRevisions
{
    /**
     * All saved revisions for this record.
     */
    public function revisions(): MorphMany
    {
        return $this->morphMany(Revision::class, 'revisionable');
    }

    /**
     * Persist the current state of this record as a revision snapshot.
     */
    public function createRevision(?string $note = null): Revision
    {
        $revision = new Revision([
            'user_id' => auth()->id(),
            'data' => json_encode([
                'attributes' => $this->getAttributes(),
                'note' => $note,
            ]),
        ]);

        return $this->revisions()->save($revision);
    }

    /**
     * Restore this record to the state captured in the given revision.
     */
    public function restoreRevision(Revision $revision): self
    {
        $data = json_decode($revision->data, true) ?? [];

        if (isset($data['attributes'])) {
            foreach ($data['attributes'] as $key => $value) {
                if ($key !== $this->getKeyName()) {
                    $this->setAttribute($key, $value);
                }
            }

            $this->save();
        }

        return $this;
    }
}
