<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{
    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if (!empty($request->get('user_id'))) {
            return response(
                $this->repository->getUsersJobs($request->get('user_id'))
            );
        }

        if (in_array($request->__authenticatedUser->user_type, [env('ADMIN_ROLE_ID'), env('SUPERADMIN_ROLE_ID')])) {
            return response(
                $this->repository->getAll($request)
            );
        }

        return null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return response(
            $this->repository->with('translatorJobRel.user')->find($id)
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return response(
            $this->repository->store($request->__authenticatedUser, $request->all())
        );
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        return response(
            $this->repository->updateJob(
                $id,
                array_except($request->all(), ['_token', 'submit']),
                $request->__authenticatedUser
            )
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        return response(
            $this->repository->storeJobEmail($request->all())
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if (empty($request->get('user_id'))) return null;
        
        return response(
            $this->repository->getUsersJobsHistory($request->get('user_id'), $request)
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        return response(
            $this->repository->acceptJob($request->all(), $request->__authenticatedUser)
        );
    }

    public function acceptJobWithId(Request $request)
    {
        return response(
            $this->repository->acceptJobWithId($request->get('job_id'), $request->__authenticatedUser)
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        return response(
            $this->repository->cancelJobAjax($request->all(), $request->__authenticatedUser)
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        return response(
            $this->repository->endJob($request->all())
        );
    }

    public function customerNotCall(Request $request)
    {
        return response(
            $this->repository->customerNotCall($request->all())
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        return response(
            $this->repository->getPotentialJobs($request->__authenticatedUser)
        );
    }

    public function distanceFeed(Request $request)
    {
        if (empty($request->input('jobid'))) {
            return 'Invalid job provided.';
        }

        if ($request->input('flagged') == 'true' && empty($request->input('admincomment'))) {
            return 'Please, add comment.';
        }

        if (
            !empty($request->input('distance')) || 
            !empty($request->input('time'))
        ) {
            Distance::whereJobId($request->input('jobid'))->update([
                'distance' => $request->input('distance'),
                'time' => $request->input('time')
            ]);
        }

        if (
            !empty($request->input('session_time')) || 
            !empty($request->input('manually_handled')) || 
            !empty($request->input('by_admin')) || 
            !empty($request->input('admincomment')) || 
            !empty($request->input('flagged'))
        ) {
            Job::whereJobId($request->input('jobid'))->update([
                'session_time' => $request->input('session_time'),
                'manually_handled' => $request->input('manually_handled') == 'true' ? 'yes' : 'no',
                'by_admin' => $request->input('by_admin') == 'true' ? 'yes' : 'no',
                'admin_comments' => $request->input('admincomment'),
                'flagged' => $request->input('flagged') == 'true' ? 'yes' : 'no',
            ]);
        }

        return response('Record updated!');
    }

    public function reopen(Request $request)
    {
        return response(
            $this->repository->reopen($request->all())
        );
    }

    public function resendNotifications(Request $request)
    {
        $job = $this->repository->find($request->input('jobid'));

        $this->repository->sendNotificationTranslator(
            $job, 
            $this->repository->jobToData($job),
            '*'
        );

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        try {
            $this->repository->sendSMSNotificationToTranslator(
                $this->repository->find($request->input('jobid'))
            );

            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()]);
        }
    }
}
