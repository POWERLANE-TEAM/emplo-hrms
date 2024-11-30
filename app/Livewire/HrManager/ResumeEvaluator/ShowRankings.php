<?php


namespace App\Livewire\HrManager\ResumeEvaluator;


use Livewire\Component;


class ShowRankings extends Component
{
    public $job_positions = [
        'job_position' => 'Data Analyst',
        'job_education' => [
            ['degree' => 'B.Sc. in Statistics', 'priority' => 'High'],
            ['degree' => 'Diploma in Data Analytics', 'priority' => 'High'],
        ],
        'job_experience' => [
            ['role' => 'Junior Data Analyst', 'years' => 2, 'priority' => 'Medium'],
            ['role' => 'Data Analyst Intern', 'years' => 1, 'priority' => 'Low'],
        ],
        'job_skills' => [
            ['skill' => 'Excel', 'priority' => 'Low'],
            ['skill' => 'R Programming', 'priority' => 'Low'],
            ['skill' => 'SQL', 'priority' => 'Low'],
        ],
    ];
   
    public $applicants = [
        [
            'applicant_name' => 'John Doe',
            'applicant_position' => 'Data Analyst',
            'applicant_education' => [
                ['degree' => 'B.Sc. in Computer Science'],
                ['degree' => 'Certification in AI/ML'],
            ],
            'applicant_experience' => [
                ['role' => '1 year Frontend Developer'],
                ['role' => '2 year Backend Developer'],
            ],
            'applicant_skills' => [
                ['skill' => 'JavaScript'],
                ['skill' => 'Python'],
                ['skill' => 'SQL'],
            ],
        ],
        [
            'applicant_name' => 'Jane Smith',
            'applicant_position' => 'Data Analyst',
            'applicant_education' => [
                ['degree' => 'B.Sc. in Statistics'],
                ['degree' => 'Diploma in Data Analytics'],
            ],
            'applicant_experience' => [
                ['role' => 'Data Analyst Intern'],
                ['role' => 'Junior Data Analyst'],
            ],
            'applicant_skills' => [
                ['skill' => 'Excel'],
                ['skill' => 'R Programming'],
                ['skill' => 'Tableau'],
            ],
        ],
    ];


    public function calculateScore($jobQualifications, $applicant)
    {
        // Priority weights
        $priorityWeights = [
            'High' => 50,
            'Medium' => 30,
            'Low' => 20,
        ];
    
        $totalPointsEarned = 0;
    
        // Count the total number of qualifications for each priority across all categories
        $priorityCounts = [
            'High' => 0,
            'Medium' => 0,
            'Low' => 0,
        ];
    
        $matchedCounts = [
            'High' => 0,
            'Medium' => 0,
            'Low' => 0,
        ];
    
        // DEBUG: Initialize a string to store the points for each priority
        // $pointsMetString = '';
    
        // Loop over each category (job_education, job_experience, job_skills)
        foreach ($jobQualifications as $category => $qualifications) {
            // Ensure $qualifications is an array before processing
            if (is_array($qualifications)) {
                // Separate qualifications by priority
                $highQualifications = array_filter($qualifications, fn($item) => $item['priority'] === 'High');
                $mediumQualifications = array_filter($qualifications, fn($item) => $item['priority'] === 'Medium');
                $lowQualifications = array_filter($qualifications, fn($item) => $item['priority'] === 'Low');
    
                // Count the number of qualifications per priority for this category
                $priorityCounts['High'] += count($highQualifications);
                $priorityCounts['Medium'] += count($mediumQualifications);
                $priorityCounts['Low'] += count($lowQualifications);
    
                // Count the number of matches per priority for this category
                $matchedCounts['High'] += count(array_uintersect(
                    $highQualifications,
                    $applicant['applicant_' . str_replace('job_', '', $category)],
                    fn($job, $applicant) => strcmp($job['degree'] ?? $job['role'] ?? $job['skill'], $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill'])
                ));
    
                $matchedCounts['Medium'] += count(array_uintersect(
                    $mediumQualifications,
                    $applicant['applicant_' . str_replace('job_', '', $category)],
                    fn($job, $applicant) => strcmp($job['degree'] ?? $job['role'] ?? $job['skill'], $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill'])
                ));
    
                $matchedCounts['Low'] += count(array_uintersect(
                    $lowQualifications,
                    $applicant['applicant_' . str_replace('job_', '', $category)],
                    fn($job, $applicant) => strcmp($job['degree'] ?? $job['role'] ?? $job['skill'], $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill'])
                ));
            }
        }
    
        // Calculate the points for each priority
        $totalPointsEarned = 0;
    
        foreach (['High', 'Medium', 'Low'] as $priority) {
            $priorityQualificationCount = $priorityCounts[$priority];
            $matchedQualificationCount = $matchedCounts[$priority];
    
            /* CALCULATION DEBUG
                echo "Total {$priority} Qualifications Count: {$priorityQualificationCount}<br>";
                echo "Total Points for {$priority} Quality: {$priorityWeights[$priority]}<br>";
                echo "Total Points per {$priority} Quality: " . ($priorityQualificationCount > 0 ? $priorityWeights[$priority] / $priorityQualificationCount : 0) . "<br>";
                echo "Total {$priority} Qualifications Met: {$matchedQualificationCount}<br>";
                echo "Total Points per {$priority} Quality that has been met: " . ($priorityQualificationCount > 0 ? ($priorityWeights[$priority] / $priorityQualificationCount) * $matchedQualificationCount : 0) . "<br><br>"; */
    
            if ($priorityQualificationCount > 0) {
                // Points per qualification for this priority
                $pointsPerQualification = $priorityWeights[$priority] / $priorityQualificationCount;
    
                // Calculate the earned points for this priority
                $earnedPointsForPriority = $pointsPerQualification * $matchedQualificationCount;
                $totalPointsEarned += $earnedPointsForPriority;
    
                /* CALCULATION DEBUG: Add this priority's earned points to the pointsMetString
                $pointsMetString .= ($earnedPointsForPriority > 0 ? number_format($earnedPointsForPriority, 2) : '0') . ' + '; */
            }
        }
    
        /* CALCULATION DEBUG. 
        Remove the last " + " and echo the summary of total points for qualifications that have been met
        $pointsMetString = rtrim($pointsMetString, ' + '); // Remove trailing plus sign
        echo "<br>Total Points for Qualifications that have been met: " . $pointsMetString . "<br>"; */
    
        // Return the percentage of points earned out of 100
        return ($totalPointsEarned / 100) * 100;
    }
    
    
    
    
    
    
    
    

    public function render()
    {
        $applicantsData = [];
    
        foreach ($this->applicants as $applicant) {
            $matchPercentage = $this->calculateScore($this->job_positions, $applicant);
    
            // Qualifications met and total qualifications count
            $metQualifications = [];
            $totalQualifications = 0;  // Total number of qualifications across all categories
            $metCount = 0;  // Count of qualifications the applicant met
    
            $categories = ['job_education', 'job_experience', 'job_skills'];
    
            foreach ($categories as $category) {
                $jobCategory = $this->job_positions[$category];
                $applicantCategory = $applicant['applicant_' . str_replace('job_', '', $category)];
    
                $totalQualifications += count($jobCategory);  // Add the total qualifications for this category
                $matched = array_uintersect(
                    $jobCategory,
                    $applicantCategory,
                    fn($job, $applicant) => strcmp(
                        $job['degree'] ?? $job['role'] ?? $job['skill'],
                        $applicant['degree'] ?? $applicant['role'] ?? $applicant['skill']
                    )
                );
    
                $metCount += count($matched);  // Add the number of qualifications met in this category
    
                // Merge matched qualifications for display
                $metQualifications = array_merge($metQualifications, array_column($matched, $category === 'job_skills' ? 'skill' : ($category === 'job_education' ? 'degree' : 'role')));
            }
    
            // Add to the applicants data
            $applicantsData[] = [
                'name' => $applicant['applicant_name'],
                'percentage' => round($matchPercentage, 2),
                'qualifications_met' => $metCount . '/' . $totalQualifications, // Display qualifications met vs total
                'qualifications_list' => implode(', ', $metQualifications),
            ];
        }
    
        // Sort applicants by percentage in descending order
        usort($applicantsData, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage']; // Descending order
        });
    
        return view('livewire.hr-manager.resume-evaluator.show-rankings', [
            'applicantsData' => $applicantsData,
        ]);
    }
    
}



