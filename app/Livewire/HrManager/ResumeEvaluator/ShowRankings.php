<?php

namespace App\Livewire\HrManager\ResumeEvaluator;

use Livewire\Component;
use App\Models\JobVacancy;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use App\Enums\ApplicationStatus;
use Livewire\Attributes\Computed;
use App\Enums\JobQualificationPriorityLevel;

class ShowRankings extends Component
{
    public $selectedJob;

    #[Locked]
    private $priorityWeights = [];

    #[Locked]
    private $hp;

    #[Locked]
    private $mp;

    #[Locked]
    private $lp;

    public $routePrefix;

    public function mount($routePrefix) {
        $this->routePrefix = $routePrefix;
    }

    public function boot()
    {
        $this->hp = JobQualificationPriorityLevel::HP->value;
        $this->mp = JobQualificationPriorityLevel::MP->value;
        $this->lp = JobQualificationPriorityLevel::LP->value;

        $priorityCases = JobQualificationPriorityLevel::cases();

        foreach ($priorityCases as $case) {
            $this->priorityWeights[$case->value] = $case->getWeight();
        }
    }

    public function calculateScore($jobQualifications, $applicant)
    {
        $totalPointsEarned = 0;

        $priorityCounts = [
            $this->hp => 0,
            $this->mp => 0,
            $this->lp => 0,
        ];

        $matchedCounts = [
            $this->hp => 0,
            $this->mp => 0,
            $this->lp => 0,
        ];

        foreach ($jobQualifications as $category => $qualifications) {
            if (! empty($qualifications)) {
                $highQualifications = array_filter($qualifications, 
                    fn($item) => $item['priority'] === $this->hp
                );

                $mediumQualifications = array_filter($qualifications, 
                    fn($item) => $item['priority'] === $this->mp
                );

                $lowQualifications = array_filter($qualifications, 
                    fn($item) => $item['priority'] === $this->lp
                );

                $priorityCounts[$this->hp] += count($highQualifications);
                $priorityCounts[$this->mp] += count($mediumQualifications);
                $priorityCounts[$this->lp] += count($lowQualifications);

                $matchedCounts[$this->hp] += count(array_uintersect(
                    $highQualifications,
                    $applicant[$category],
                    fn($job, $applicant) => strcasecmp(
                        $job['degree'] ?? $job['role'] ?? $job['skill'], 
                        $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill']
                    )
                ));

                $matchedCounts['Medium'] += count(array_uintersect(
                    $mediumQualifications,
                    $applicant[$category],
                    fn($job, $applicant) => strcasecmp(
                        $job['degree'] ?? $job['role'] ?? $job['skill'], 
                        $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill']
                    )
                ));

                $matchedCounts['Low'] += count(array_uintersect(
                    $lowQualifications,
                    $applicant[$category],
                    fn($job, $applicant) => strcasecmp(
                        $job['degree'] ?? $job['role'] ?? $job['skill'],
                        $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill']
                    )
                ));
            }
        }            

        $totalPointsEarned = 0;

        foreach ([$this->hp, $this->mp, $this->lp] as $priority) {
            $priorityQualificationCount = $priorityCounts[$priority];
            $matchedQualificationCount = $matchedCounts[$priority];

            // CALCULATION DEBUG
            // dump([
            //     "Total {$priority} Qualifications Count" => $priorityQualificationCount,
            //     "Total Points for {$priority} Quality" => $$this->priorityWeights[$priority],
            //     "Total Points per {$priority} Quality" => $priorityQualificationCount > 0 ? $$this->priorityWeights[$priority] / $priorityQualificationCount : 0,
            //     "Total {$priority} Qualifications Met" => $matchedQualificationCount,
            //     "Total Points per {$priority} Quality that has been met" => $priorityQualificationCount > 0 ? ($$this->priorityWeights[$priority] / $priorityQualificationCount) * $matchedQualificationCount : 0
            // ]);
                
            if ($priorityQualificationCount > 0) {
                $pointsPerQualification = $this->priorityWeights[$priority] / $priorityQualificationCount;

                $earnedPointsForPriority = $pointsPerQualification * $matchedQualificationCount;
                $totalPointsEarned += $earnedPointsForPriority;

                // CALCULATION DEBUG: Add this priority's earned points to the pointsMetString
                // $pointsMetString .= ($earnedPointsForPriority > 0 ? number_format($earnedPointsForPriority, 2) : '0') . ' + ';
            }
        }

        // CALCULATION DEBUG.
        // $pointsMetString = rtrim($pointsMetString);
        // dump("Total Points for Qualifications that have been met: {$pointsMetString}.");

        return ($totalPointsEarned / 100) * 100;
    }


    public function generateRankings()
    {
        if (! $this->selectedJob) {
            return [];
        }

        $selectedValue = $this->selectedJob['value'];

        $selectedJob = collect($this->vacantJobs)
            ->firstWhere('id', (int) $selectedValue);

        $jobQualifications = $selectedJob ? [
            'education' => $selectedJob['education'] ?? [],
            'experience' => $selectedJob['experience'] ?? [],
            'skills' => $selectedJob['skills'] ?? [],
        ] : [];

        $applicantsData = [];

        foreach ($this->applicants as $applicant) {
            if ($applicant['jobTitleId'] === $selectedJob['id']) {

                $matchPercentage = $this->calculateScore($jobQualifications, $applicant);

                $metQualifications = [];
                $totalQualifications = 0;
                $metCount = 0;

                $categories = ['education', 'experience', 'skills'];

                foreach ($categories as $category) {
                    if (isset($jobQualifications[$category]) && isset($applicant[$category])) {
                        $jobCategory = $jobQualifications[$category];
                        $applicantCategory = $applicant[$category];
                
                        if (! empty($jobCategory) && ! empty($applicantCategory)) {
                            $totalQualifications += count($jobCategory);
                            $matched = array_uintersect(
                                $jobCategory,
                                $applicantCategory,
                                fn($job, $applicant) => strcasecmp(
                                    $job['degree'] ?? $job['role'] ?? $job['skill'] ?? '',
                                    $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill'] ?? '')
                            );
                
                            $metCount += count($matched);
                
                            $metQualifications = array_merge($metQualifications, array_column(
                                $matched,
                                $category === 'skills' ? 'skill' : ($category === 'education' ? 'degree' : 'role')
                            ));
                        }
                    }
                }                

                $applicantsData[] = [
                    'application_id' => $applicant['applicationId'],
                    'name' => $applicant['name'],
                    'email' => $applicant['email'],
                    'percentage' => round($matchPercentage, 2),
                    'qualifications_met' => "{$metCount}/{$totalQualifications}",
                    'qualifications_list' => implode(', ', $metQualifications),
                ];
            }
        }

        usort($applicantsData, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        return $applicantsData;
    }

    #[Computed]
    public function vacancies()
    {
        return JobVacancy::with([
            'jobTitle',
            'applications.applicant.educations',
            'applications.applicant.experiences',
            'applications.applicant.skills',
            'jobTitle.educations',
            'jobTitle.experiences',
            'jobTitle.skills',    
        ])
        ->get()
        ->reject(function ($item) {
            return (
                ($item->vacancy_count <= 0 &&
                (Carbon::parse($item->application_deadline_at)->isPast() || 
                    ! is_null($item->application_deadline_at))) &&
                $item->applications->every(function ($item) {
                    return (
                        ! is_null($item->hired_at) ||
                        $item->is_passed === 1 ||
                        $item->application_status_id !== ApplicationStatus::PENDING->value
                    );
                })
            );
        });
    }

    #[Computed]
    public function listOfOpenJobs()
    {
        return $this->vacancies->mapWithKeys(function ($item) {
                return [
                    $item->jobTitle->job_title_id => $item->jobTitle->job_title,
                ];
            })->toArray();
    }

    #[Computed]
    public function applicants()
    {
        return $this->vacancies->flatMap(function ($item) {
            return $item->applications
                ->map(function ($application) use ($item) {
                    return [
                        'applicationId' => $application->application_id,
                        'name' => $application->applicant->full_name,
                        'email' => $application->applicant->email,
                        'applyingFor' => $item->jobTitle->job_title,
                        'jobTitleId' => $item->jobTitle->job_title_id,
                        'education' => $application->applicant->educations->map(function ($education) {
                            return [
                                'degree' => $education->education,
                            ];
                        })->toArray(),
                        'experience' => $application->applicant->experiences->map(function ($experience) {
                            return [
                                'role' => $experience->experience_desc,
                            ];
                        })->toArray(),
                        'skills' => $application->applicant->skills->map(function ($skill) {
                            return [
                                'skill' => $skill->skill,
                            ];
                        })->toArray(),
                    ];
                });
            })->toArray();
    }
    
    
    #[Computed]
    public function vacantJobs()
    {
        return $this->vacancies->map(function ($item) {
            return [
                'id' => $item->jobTitle->job_title_id,
                'title' => $item->jobTitle->job_title,
                'education' => $item->jobTitle->educations->map(function ($item) {
                    return [
                        'degree' => $item->keyword,
                        'priority' => $item->priority,
                    ];
                })->toArray(),
                'experience' => $item->jobTitle->experiences->map(function ($item) {
                    return [
                        'role' => $item->keyword,
                        'priority' => $item->priority,
                    ];
                })->toArray(),
                'skills' => $item->jobTitle->skills->map(function ($item) {
                    return [
                        'skill' => $item->keyword,
                        'priority' => $item->priority,
                    ];
                })->toArray(),
            ];
        })
        ->unique('id')
        ->toArray();
    }

    public function render()
    {
        $applicantsData = $this->generateRankings();
        $totalApplicants = count($applicantsData);
        $selectedValue = $this->selectedJob['value'] ?? null;
        $selectedJobName = null;

        if ($selectedValue) {
            $selectedPositionName = collect($this->vacantJobs)
                ->firstWhere('id', (int) $selectedValue)['title'] ?? 'N/A';

            $selectedJobName = $selectedPositionName;
        }

        return view('livewire.hr-manager.resume-evaluator.show-rankings', 
            compact(
                'applicantsData', 
                'totalApplicants', 
                'selectedJobName'
            )
        );
    }
}