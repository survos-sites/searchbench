<?php

namespace App\Workflow;

use App\Entity\Official;
use Survos\StateBundle\Attribute\Place;
use Survos\StateBundle\Attribute\Transition;
use Survos\StateBundle\Attribute\Workflow;

// See events at https://symfony.com/doc/current/workflow.html#using-events

#[Workflow(supports: [Official::class], name: self::WORKFLOW_NAME)]
class OfficialWorkflowInterface
{
    // This name is used for injecting the workflow into a service
    // #[Target(OfficialWorkflowInterface::WORKFLOW_NAME)] private WorkflowInterface $workflow
    public const WORKFLOW_NAME = 'OfficialWorkflow';

    #[Place(info: 'loaded from JSON')]
    public const PLACE_NEW = 'new';
    #[Place(info: 'enhanced with wikidata; P18 images registered as media')]
    public const PLACE_DETAILS = 'details';

    #[Transition([self::PLACE_NEW], self::PLACE_DETAILS, transport: 'official_fetch_wiki',
        info: "scrape wiki data + register P18 images as media")]
    public const TRANSITION_FETCH_WIKI = 'fetch_wiki';

}
