<?php

namespace Tests\Unit;

use App\Form;
use App\Email;
use App\Profile;
use App\ProfileEvent;
use Illuminate\Http\Request;
use App\Enums\ProfileEventType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileEventTest extends ModelTestCase
{
    use RefreshDatabase;

    /**
     * The model instance to test.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = ProfileEvent::class;

    /** @test */
    public function it_correctly_records_the_applied_tag_event()
    {
        $profile = factory(Profile::class)->create();

        $event = ProfileEvent::recordAppliedTagEvent($profile, 'tag value');

        $this->assertDatabaseHas(ProfileEvent::getTableName(), [
            'event_type' => ProfileEventType::AppliedTag,
            'profile_id' => $profile->id,
        ]);

        $this->assertEquals('tag value', $event->data['tag']);
    }

    /** @test */
    public function it_correctly_records_the_bounced_event()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_correctly_records_the_confirmed_form_submission_event()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_correctly_records_the_issued_spam_complaint_event()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_correctly_records_the_marked_as_undeliverable_event()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_correctly_records_the_received_email_event()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_correctly_records_the_removed_tag_event()
    {
        $profile = factory(Profile::class)->create();

        $event = ProfileEvent::recordRemovedTagEvent($profile, 'tag value');

        $this->assertDatabaseHas(ProfileEvent::getTableName(), [
            'event_type' => ProfileEventType::RemovedTag,
            'profile_id' => $profile->id,
        ]);

        $this->assertEquals('tag value', $event->data['tag']);
    }

    /** @test */
    public function it_correctly_records_the_submitted_form_event()
    {
        $profile = factory(Profile::class)->create();
        $form = factory(Form::class)->create();

        $event = ProfileEvent::recordSubmittedFormEvent($profile, $form, [
            'test-field' => 'test value',
        ]);

        $this->assertDatabaseHas(ProfileEvent::getTableName(), [
            'event_type' => ProfileEventType::SubmittedForm,
            'profile_id' => $profile->id,
        ]);

        $this->assertEquals($form->id, $event->data['form_id']);
        $this->assertEquals('test value', $event->data['form_data']['test-field']);
    }

    /** @test */
    public function it_correctly_records_the_updated_email_address_event()
    {
        $profile = factory(Profile::class)->create();
        $newEmail = faker()->email;

        $event = ProfileEvent::recordUpdatedEmailAddressEvent($profile, $profile->email, $newEmail);

        $this->assertDatabaseHas(ProfileEvent::getTableName(), [
            'event_type' => ProfileEventType::UpdatedEmailAddress,
            'profile_id' => $profile->id,
        ]);

        $this->assertEquals($profile->email, $event->data['old_email']);
        $this->assertEquals($newEmail, $event->data['new_email']);
    }

    /** @test */
    public function it_correctly_records_the_visited_page_event()
    {
        $url = 'http://test-domain.com/test-uri';

        $profile = factory(Profile::class)->create();
        $request = Request::create($url);

        $event = ProfileEvent::recordVisitedPageEvent($profile, $request);

        $this->assertDatabaseHas(ProfileEvent::getTableName(), [
            'event_type' => ProfileEventType::VisitedPage,
            'profile_id' => $profile->id,
        ]);

        $this->assertEquals($event->data['url'], $url);
    }
}
