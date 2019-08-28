<?php

namespace Tests\Unit;

use App\Form;
use App\User;
use App\Concerns\Paginatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = Form::class;

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
    public function it_has_many_fields()
    {
        $this->assertInstanceOf(HasMany::class, app($this->model)->fields());
    }

    /** @test */
    public function it_has_many_submissions()
    {
        $this->assertInstanceOf(HasMany::class, app($this->model)->submissions());
    }

    /** @test */
    public function a_user_can_subscribe_to_a_form()
    {
        $form = factory(Form::class)->create();

        $form->subscribeToNotifications(
            factory(User::class)->create()
        );

        $form->refresh();

        $this->assertCount(1, $form->subscribers);

        $form->subscribeToNotifications(
            factory(User::class)->create()
        );

        $form->refresh();

        $this->assertCount(2, $form->subscribers);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_a_form()
    {
        $form = factory(Form::class)->create();
        $user = factory(User::class)->create();

        $form->subscribeToNotifications($user);

        $form->unsubscribeFromNotifications($user);

        $this->assertEmpty($form->subscribers);
    }
}
