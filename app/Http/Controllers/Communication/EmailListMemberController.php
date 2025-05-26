<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Enums\ModelMorphName;
use App\Http\Controllers\Controller;
use App\Http\Resources\Member\MemberResource;
use App\Models\Email;
use App\Models\Member;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class EmailListMemberController extends Controller
{
    public function __invoke(): Response
    {
        Gate::authorize('create', [Email::class, ModelMorphName::MEMBER]);
        $members = Member::all();

        return Inertia::render('communication/emails/members', [
            'members' => MemberResource::collection($members),
        ]);
    }
}
