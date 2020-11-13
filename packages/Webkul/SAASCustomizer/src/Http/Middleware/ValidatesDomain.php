<?php

namespace Webkul\SAASCustomizer\Http\Middleware;

use Webkul\SAASCustomizer\Repositories\Super\CompanyRepository;
use Webkul\Core\Repositories\ChannelRepository;

use Closure;
use Validator;

class ValidatesDomain
{
    /**
     * @var CompanyRepository Instance
     */
    protected $companyRepository;

    /**
     * @var ChannelRepository Instance
     */
    protected $channelRepository;

    public function __construct(
        CompanyRepository $companyRepository,
        ChannelRepository $channelRepository
    )   {
        $this->companyRepository = $companyRepository;

        $this->channelRepository = $channelRepository;
    }

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $primaryServerName = config('app.url');

        $currentURL = $_SERVER['SERVER_NAME'];

        $params['domain'] = $currentURL;

        $validator = Validator::make($params, [
            'domain' => 'required|ip'
        ]);

        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        }

        //restricts the IP address usage to access the system
        if ($validator->fails()) {
            //case where IP validation fails
            if (str_contains($currentURL, 'http://')) {
                $currentURL = explode('http://', $currentURL)[1];
            } else if (str_contains($currentURL, 'http://')) {
                $currentURL = explode('http://', $currentURL)[1];
            }
        } else {
            //case where IP validation passes then it should redirect to the main domain
            return redirect()->route('company.create.index');
        }

        if (str_contains($primaryServerNameWithoutProtocol, '/')) {
            $primaryServerNameWithoutProtocol = explode('/', $primaryServerNameWithoutProtocol)[0];
        }

        if ($currentURL == $primaryServerNameWithoutProtocol) {
            if (request()->is('company/*') || request()->is('super/*')) {
                return $next($request);
            } else {
                return redirect()->route('company.create.index');
            }
        } else {
            if ((request()->is('company/*') || request()->is('super/*')) && ! request()->is('company/seed-data')) {
                throw new \Exception('not_allowed_to_visit_this_section', 400);
            } else {
                $company = $this->companyRepository->findWhere(['domain' => $currentURL]);

                if (count($company) == 1) {
                    return $next($request);
                } else if (count($company) == 0) {
                    $cname = explode("www.", $currentURL);
                    
                    if (count($cname) > 1) {
                        $company = $this->companyRepository->where('cname', $cname)->orWhere('cname', $currentURL)->get();
                    } else {
                        $company = $this->companyRepository->findWhere(['cname' => $currentURL]);
                    }

                    if (count($company) == 1) {
                        return $next($request);
                    } else {
                        $channel = $this->channelRepository->findOneByfield('hostname', $currentURL);

                        if ( isset($channel->id) ) {
                            return $next($request);
                        } else {
                            $path = 'saas';

                            return $this->response($path, 400, trans('saas::app.admin.tenant.exceptions.domain-not-found'), 'domain_not_found');
                            // throw new \Exception('domain_not_found', 400);
                        }
                    }
                } else {
                    return $next($request);
                }
            }
        }
    }

    private function response($path, $statusCode, $message = null, $type = null)
    {
        if (request()->expectsJson()) {
            return response()->json([
                    'error' => isset($this->jsonErrorMessages[$statusCode])
                        ? $this->jsonErrorMessages[$statusCode]
                        : trans('saas::app.tenant.registration.something-wrong-1')
                ], $statusCode);
        }

        if ($type == null) {
            return response()->view("{$path}::errors.{$statusCode}", ['message' => $message, 'status' => $statusCode], $statusCode);
        } else {
            return response()->view("{$path}::errors.{$type}", ['message' => $message, 'status' => $statusCode], $statusCode);
        }
    }
}