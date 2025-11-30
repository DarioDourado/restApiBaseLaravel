<?php

namespace App\Constants;

class ErrorMessages
{
    // User errors
    public const USER_RETRIEVAL_FAILED = 'errors.user.retrieval_failed';
    public const USER_NOT_FOUND = 'errors.user.not_found';
    public const USER_CREATION_FAILED = 'errors.user.creation_failed';
    public const USER_UPDATE_FAILED = 'errors.user.update_failed';
    public const USER_DELETION_FAILED = 'errors.user.deletion_failed';

    // Validation errors
    public const VALIDATION_FAILED = 'errors.validation.failed';

    // Auth errors
    public const UNAUTHORIZED = 'errors.auth.unauthorized';
    public const FORBIDDEN = 'errors.auth.forbidden';

    // General errors
    public const SERVER_ERROR = 'errors.general.server_error';
    public const NOT_FOUND = 'errors.general.not_found';
    public const BAD_REQUEST = 'errors.general.bad_request';
}

