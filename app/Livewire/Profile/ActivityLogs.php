<?php

namespace App\Livewire\Profile;

use Carbon\Carbon;
use Livewire\Component;

class ActivityLogs extends Component
{
    // BACK-END REPLACE: Database fetching only. The rest of the back-end are handled.
    public $events = [
        [
            'date' => '2024-12-10 12:23:52',
            'logs' => [
                [
                    'time' => '2024-12-10 08:30:00',
                    'title' => 'Aaron Young completed the final draft of the business report.',
                    'previous_value' => 'Draft Version 1.0',
                    'new_value' => 'Final Version 1.1',
                    'platform' => 'Windows - Chrome',
                    'ip_address' => '192.168.1.101',
                ],
                [
                    'time' => '2024-12-10 09:00:00',
                    'title' => 'Mia Brown updated the marketing plan for Q1.',
                    'previous_value' => 'Original Plan',
                    'new_value' => 'Updated Plan with new KPIs',
                    'platform' => 'Windows - Chrome',
                    'ip_address' => '192.168.1.102',
                ],
                [
                    'time' => '2024-12-10 11:45:00',
                    'title' => 'Noah Smith revised the sales strategy for the upcoming quarter.',
                    'previous_value' => 'Q4 Strategy',
                    'new_value' => 'Q1 Strategy',
                    'platform' => 'Windows - Chrome',
                    'ip_address' => '192.168.1.103',
                ],
                [
                    'time' => '2024-12-10 13:30:00',
                    'title' => 'Emma Wilson finalized the client presentation slides.',
                    'previous_value' => 'Initial Draft',
                    'new_value' => 'Finalized Version',
                    'platform' => 'Windows - Chrome',
                    'ip_address' => '192.168.1.104',
                ],
                [
                    'time' => '2024-12-10 16:00:00',
                    'title' => 'Oliver Davis updated the company’s privacy policy.',
                    'previous_value' => 'Privacy Policy v1',
                    'new_value' => 'Privacy Policy v2',
                    'platform' => 'Windows - Chrome',
                    'ip_address' => '192.168.1.105',
                ],
            ],
        ],
        [
            'date' => '2024-12-11 12:23:52',
            'logs' => [
                [
                    'time' => '2024-12-11 09:00:00',
                    'title' => 'Daniel Scott updated project budget to accommodate new expenses.',
                    'previous_value' => '5,000',
                    'new_value' => '7,000',
                    'platform' => 'Windows - Firefox',
                    'ip_address' => '192.168.2.201',
                ],
                [
                    'time' => '2024-12-11 10:30:00',
                    'title' => 'Lucas Turner added a new task to the ongoing project.',
                    'platform' => 'Windows - Firefox',
                    'ip_address' => '192.168.2.202',
                ],
                [
                    'time' => '2024-12-11 11:15:00',
                    'title' => 'Olivia Parker logged in.',
                    'platform' => 'Windows - Firefox',
                    'ip_address' => '192.168.2.203',
                ],
                [
                    'time' => '2024-12-11 14:00:00',
                    'title' => 'Ethan Lee updated the project timeline to reflect new deadlines.',
                    'previous_value' => 'March 30, 2024',
                    'new_value' => 'April 15, 2024',
                    'platform' => 'Windows - Firefox',
                    'ip_address' => '192.168.2.204',
                ],
                [
                    'time' => '2024-12-11 15:30:00',
                    'title' => 'Sophia Davis sent out the updated project documentation.',
                    'previous_value' => 'N/A',
                    'new_value' => 'Sent',
                    'platform' => 'Windows - Firefox',
                    'ip_address' => '192.168.2.205',
                ],
            ],
        ],
        [
            'date' => '2024-12-09 12:23:52',
            'logs' => [
                [
                    'time' => '2024-12-09 07:45:00',
                    'title' => 'Liam Johnson completed the first draft of the client proposal.',
                    'previous_value' => 'Proposal Draft',
                    'new_value' => 'Final Proposal',
                    'platform' => 'MacOS - Safari',
                    'ip_address' => '192.168.3.301',
                ],
                [
                    'time' => '2024-12-09 09:15:00',
                    'title' => 'Charlotte Martinez updated the product design with client feedback.',
                    'previous_value' => 'Old Design',
                    'new_value' => 'New Design with Feedback',
                    'platform' => 'MacOS - Safari',
                    'ip_address' => '192.168.3.302',
                ],
                [
                    'time' => '2024-12-09 10:30:00',
                    'title' => 'Elijah Brown revised project goals based on team input.',
                    'platform' => 'MacOS - Safari',
                    'ip_address' => '192.168.3.303',
                ],
                [
                    'time' => '2024-12-09 12:45:00',
                    'title' => 'Amelia Garcia sent out the project timeline to stakeholders.',
                    'previous_value' => 'N/A',
                    'new_value' => 'Sent',
                    'platform' => 'MacOS - Safari',
                    'ip_address' => '192.168.3.304',
                ],
                [
                    'time' => '2024-12-09 14:00:00',
                    'title' => 'James White updated the project website with new features.',
                    'previous_value' => 'Old Version',
                    'new_value' => 'New Version with Features',
                    'platform' => 'MacOS - Safari',
                    'ip_address' => '192.168.3.305',
                ],
            ],
        ],
    ];

    public function render()
    {
        foreach ($this->events as &$event) {
            $event['raw_date'] = Carbon::parse($event['date']);

            $event['formatted_date'] = $event['raw_date']->format('M j, Y');

            foreach ($event['logs'] as &$log) {
                $log['raw_time'] = Carbon::parse($log['time']);
                $log['time'] = $log['raw_time']->format('g:ia');
            }

            $event['logs'] = collect($event['logs'])->sortByDesc('raw_time')->values()->toArray();
        }

        $this->events = collect($this->events)->sortByDesc('raw_date')->values()->toArray();

        return view('livewire.profile.activity-logs');
    }
}
