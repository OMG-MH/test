<?php

namespace App\Constants;

class ManageStatus
{
    // General Active/Inactive Status
    const INACTIVE = 0;
    const ACTIVE   = 1;

    // Yes/No Status
    const NO  = 0;
    const YES = 1;

    // Verification Status
    const UNVERIFIED = 0;
    const VERIFIED   = 1;
    const PENDING    = 2;

    // Payment Status
    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_CANCEL   = 3;

    // Job Status
    const JOB_REJECTED    = 0;
    const JOB_APPROVED    = 1;
    const JOB_PENDING     = 2;
    const JOB_PAUSED      = 3;
    const JOB_UNAVAILABLE = 4;

    // Job Proof Requirement Status
    const JOB_PROOF_OPTIONAL = 1;
    const JOB_PROOF_REQUIRED = 2;

    // Job Application Status
    const JOB_APPLICATION_REJECTED = 0;
    const JOB_APPLICATION_APPROVED = 1;
    const JOB_APPLICATION_PENDING  = 2;

    // Assigned Job Status
    const ASSIGNED_JOB_IN_PROGRESS = 2;
    const ASSIGNED_JOB_COMPLETED   = 1;
    const ASSIGNED_JOB_DISPUTED    = 8;
    const ASSIGNED_JOB_SETTLED     = 4;

    // Freelancer Status
    const FREELANCER_REJECTED = 0;
    const FREELANCER_ACTIVE   = 1;
    const FREELANCER_PENDING  = 2;
    const FREELANCER_BANNED   = 3;
    const FREELANCER_NOT      = 5;
}
