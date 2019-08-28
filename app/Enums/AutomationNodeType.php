<?php

namespace App\Enums;

final class AutomationNodeType extends Enum
{
    // Perform an action, such as subscribe to a campaign or send a one-off email.
    const Action = 'action';

    // Send people down a single path based on selected criteria.
    const Decision = 'decision';

    // Perform several actions at the same time.
    const ParallelPath = 'parallel-path';

    // Define a goal that will pull people to this point in the workflow when achieved.
    const Goal = 'goal';

    // Wait for a given period of time before continuing down the path.
    const Delay = 'delay';

    // Sent traffic to 2-5 different paths to determine which is the most effective.
    const SplitTest = 'split-test';

    // Exit the path the person is currently on.
    const Exit = 'exit';
}
