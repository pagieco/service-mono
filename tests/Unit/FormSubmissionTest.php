<?php

namespace Tests\Unit;

use App\Form;
use App\User;
use App\FormSubmission;
use App\Concerns\Paginatable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmissionTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = FormSubmission::class;

    /** @test */
    public function it_correctly_implements_the_paginatable_concern()
    {
        $this->assertTrue(in_array(Paginatable::class, class_uses($this->model)));
    }

    /** @test */
    public function it_belongs_to_a_team()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->team());
    }

    /** @test */
    public function it_belongs_to_a_domain()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->domain());
    }

    /** @test */
    public function it_belongs_to_a_form()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->form());
    }

    /** @test */
    public function it_triggers_a_notification_job_on_the_parent_form_when_a_submission_is_created_and_send_notification_to_is_set()
    {
        Notification::fake();

        $form = factory(Form::class)->create();

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $form->subscribeToNotifications($user1);

        factory(FormSubmission::class)->create([
            'form_id' => $form->id,
        ]);

        Notification::assertSentTo($user1, FormSubmissionNotification::class);
        Notification::assertNotSentTo($user2, FormSubmissionNotification::class);
    }
}
