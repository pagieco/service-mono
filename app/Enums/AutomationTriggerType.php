<?php

namespace App\Enums;

final class AutomationTriggerType extends Enum
{
    // Triggers when a tag is applied.
    const AppliedTag = 'applied-tag';

    // Triggers when a person's lead threshold is met.
    const BecameLead = 'became-lead';

    // Triggers when a person's lead score drops below the initial score.
    const BecameNonPropspect = 'became-non-prospect';

    // Triggers when a person became a potential lead.
    const BecamePotentialLead = 'became-potential-lead';

    // Triggers when a hard bounce is recorded for one of your emails.
    const Bounced = 'bounced';

    // Triggers when a person clicks a link in an email or on your website.
    const ClickedLink = 'clicked-link';

    // Triggers when a person has completed a campaign.
    const CompletedCampaign = 'completed-campaign';

    // Triggers when a person exits an automation workflow through an exit node.
    const CompletedAutomation = 'completed-automation';

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

    // Triggers when a person is removed from a campaign. This will happen when a person unsubscribes
    // from a campaign, or when a person achieves a goal in an automation workfow.
    const RemovedFromAutomation = 'removed-from-automation';

    // Triggers when a person replies to an email.
    const RepliedToEmail = 'replied-to-email';

    // Triggers when a person enters into an automation workflow.
    const StartedAutomation = 'started-automation';

    // Triggers when a person submits a form.
    const SubmittedForm = 'submitted-form';

    // Triggers when a person became subscribed to a campaign.
    const SubscribedToCampaign = 'subscribed-to-campaign';

    // Triggers when a new person is created in your account.
    const SubscriberCreated = 'subscriber-created';

    // Triggers when a person became unsubscribed from a campaign.
    const UnsubscribedFromCampaign = 'unsubscribe-from-campaign';

    // Triggers when a person becomes unsubscribed from your list. When a person
    // unsubscribes from all mails, their status is marked as undeliverable.
    const UnsubscribedFromAllMailings = 'unsubscribed-from-all-mailings';

    // Triggers when a person's email address becomes updated.
    const UpdatedEmailAddress = 'updated-email-address';

    // Triggers when an identified person visits a web page.
    const VisitedPage = 'visited-page';
}
