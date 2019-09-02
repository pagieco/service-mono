<?php

namespace App\Enums;

final class ProfileEventType extends Enum
{
    // Triggers when a tag is applied.
    const AppliedTag = 'applied-tag';

    // Triggers when a hard bounce is recorded for one of your emails.
    const Bounced = 'bounced';

    // Triggers when a person confirms their subscription after submitting a form.
    const ConfirmedFormSubmission = 'confirmed-form-submission';

    // Triggers when a person issues a spam complaint against on of your emails.
    const IssuedSpamComplaint = 'issued-spam-complaint';

    // Triggers when someone's email address records a hard bounce, or when
    // a spam complaint is issued against one of your emails.
    const MarkedAsUndeliverable = 'marked-as-undeliverable';

    // Triggers when a person opens an email.
    const OpenedEmail = 'opened-email';

    // Triggers when a person receives an email.
    const ReceivedEmail = 'received-email';

    // Triggers when a tag is removed from a person.
    const RemovedTag = 'removed-tag';

    // Triggers when a person submits a form.
    const SubmittedForm = 'submitted-form';

    // Triggers when a person's email address becomes updated.
    const UpdatedEmailAddress = 'updated-email-address';

    // Triggers when an identified person visits a web page.
    const VisitedPage = 'visited-page';
}
