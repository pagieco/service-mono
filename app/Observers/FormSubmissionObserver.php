<?php


namespace App\Observers;

use App\FormSubmission;
use App\Notifications\FormSubmissionNotification;

class FormSubmissionObserver
{
    /**
     * Handle the model "created" event.
     *
     * @param  \App\FormSubmission $submission
     * @return void
     */
    public function created(FormSubmission $submission)
    {
        foreach ($submission->form->subscribers as $subscriber) {
            $subscriber->notify(new FormSubmissionNotification($submission));
        }
    }
}
