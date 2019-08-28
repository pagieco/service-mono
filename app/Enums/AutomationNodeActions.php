<?php

namespace App\Enums;

final class AutomationNodeActions extends Enum
{
    // Apply a given tag to the person.
    const ApplyTag = 'apply-tag';

    // Deletes a person (and all of their data) from your account.
    const DeletePerson = 'delete-person';

    // If lead scoring is enabled, begin lead scoring for this person.
    const FlagAsPropspect = 'flag-as-prospect';

    // Transfers a person from one campaign subscription to another. The person
    // is removed and unsubscribed from the original campaign.
    const MoveToOtherCampaign = 'move-to-other-campaign';

    // Tracks a given conversion for the person.
    const RecordConversion = 'record-conversion';

    // Records a given custom event for the person.
    const RecordCustomEvent = 'record-custom-event';

    // Remove a given tag from the person.
    const RemoveTag = 'remove-tag';

    // Remove the person from the beginning of a particular campaign.
    const RemoveFromCampaign = 'remove-from-campaign';

    // Remove the person from an automation workflow.
    const RemoveFromAutomation = 'remove-from-automation';

    // Restart the person from the beginning of a particular campaign.
    const RestartCampaign = 'restart-campaign';

    // Add the person to a particular campaign.
    const SendCampaign = 'send-campaign';

    // send a notification with information about this person.
    const SendNotificationEmail = 'send-notification-email';

    // Send a special email to the person.
    const SendOneOffEmail = 'send-one-off-email';

    // Send a json or form encoded request to a URL.
    const SendHTTPPost = 'send-http-post';

    // Set a custom field for the person.
    const SetCustomFieldValue = 'set-custom-field-value';

    // Add the person to an automation workflow.
    const StartAutomation = 'start-automation';

    // If lead scoring is enabled, stop lead scoring for this person.
    const UnflagAsPropspect = 'unflag-as-propspect';

    // Unsubscribe the person from a campaign or all mailings.
    const UnsubscribePerson = 'unsubscribe-person';
}
