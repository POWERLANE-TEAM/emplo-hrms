<?php

namespace App\Livewire\Notifications;

use Livewire\Component;

class Notifs extends Component
{
    public $generalNotifications = [];

    public $urgentNotifications = [];

    public function mount()
    {
        // Notification templates. This can be added/revised.
        $notificationTemplates = [
            'approval_updates' => "{actor} approved {subject}'s {type} form.",
            'form_data_entries' => 'New {type} request for {subject}.',
            'accomplishment_updates' => "{actor} completed the assignment of scores for {subject}'s {type}.",
            'new_applicants' => 'New applicant {subject} has submitted a resume for the position of {job_position}.',
            'performance_evaluation' => "{subject}'s performance evaluation for the {evaluation_period} is now open for review.",
        ];

        // BACK-END REPLACE: generalDate and urgentData
        $generalData = [
            [
                'template' => 'approval_updates',
                'data' => [
                    'actor' => 'Supervisor Analyn Edomiguez',
                    'subject' => 'Emilio Aguinaldo',
                    'type' => 'Overtime Summary',
                ],
                'label' => ['Overtime', 'Approval'],
                'time' => '2 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=Analyn+Edomiguez',
            ],
            [
                'template' => 'form_data_entries',
                'data' => [
                    'type' => 'Leave',
                    'subject' => 'Cristian Manalang',
                ],
                'label' => ['Leave', 'Approval'],
                'time' => '5 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=Cristian+Manalang',
            ],
            [
                'template' => 'accomplishment_updates',
                'data' => [
                    'actor' => 'Manager Sarah Cruz',
                    'subject' => 'Mark Reyes',
                    'type' => 'Annual Performance Evaluation',
                ],
                'label' => ['Performance', 'Completion'],
                'time' => '1 day ago',
                'profile' => 'https://ui-avatars.com/api/?name=Sarah+Cruz',
            ],
            [
                'template' => 'new_applicants',
                'data' => [
                    'subject' => 'Jane Smith',
                    'job_position' => 'Software Developer',
                ],
                'label' => ['Applicants', 'New'],
                'time' => '3 days ago',
                'profile' => 'https://ui-avatars.com/api/?name=Jane+Smith',
            ],
            [
                'template' => 'approval_updates',
                'data' => [
                    'actor' => 'HR Officer Karen Lim',
                    'subject' => 'James Santos',
                    'type' => 'Training Request',
                ],
                'label' => ['Training', 'Approval'],
                'time' => '4 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=Karen+Lim',
            ],
            [
                'template' => 'form_data_entries',
                'data' => [
                    'type' => 'Overtime',
                    'subject' => 'Alex Garcia',
                ],
                'label' => ['Overtime', 'Request'],
                'time' => '6 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=Alex+Garcia',
            ],
        ];

        $urgentData = [
            [
                'template' => 'performance_evaluation',
                'data' => [
                    'subject' => 'John Doe',
                    'evaluation_period' => '3-month period',
                ],
                'label' => ['Evaluation', 'Reminder'],
                'time' => '1 day ago',
                'profile' => 'https://ui-avatars.com/api/?name=John+Doe',
            ],
            [
                'template' => 'form_data_entries',
                'data' => [
                    'type' => 'Incident',
                    'subject' => 'HR Department',
                ],
                'label' => ['Incident', 'Pending'],
                'time' => '3 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=HR+Department',
            ],
            [
                'template' => 'performance_evaluation',
                'data' => [
                    'subject' => 'Emily Davis',
                    'evaluation_period' => '5-month period',
                ],
                'label' => ['Evaluation', 'Urgent'],
                'time' => '2 days ago',
                'profile' => 'https://ui-avatars.com/api/?name=Emily+Davis',
            ],
            [
                'template' => 'form_data_entries',
                'data' => [
                    'type' => 'Late Submission',
                    'subject' => 'Finance Department',
                ],
                'label' => ['Submission', 'Pending'],
                'time' => '5 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=Finance+Department',
            ],
            [
                'template' => 'performance_evaluation',
                'data' => [
                    'subject' => 'Michael Tan',
                    'evaluation_period' => 'Final Evaluation',
                ],
                'label' => ['Evaluation', 'Urgent'],
                'time' => '6 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=Michael+Tan',
            ],
            [
                'template' => 'form_data_entries',
                'data' => [
                    'type' => 'Policy Update',
                    'subject' => 'HR Department',
                ],
                'label' => ['Policy', 'Urgent'],
                'time' => '4 hours ago',
                'profile' => 'https://ui-avatars.com/api/?name=HR+Department',
            ],
        ];

        $this->sortNotificationsByTime($generalData);
        $this->sortNotificationsByTime($urgentData);

        $this->generalNotifications = $this->processNotifications($generalData, $notificationTemplates);
        $this->urgentNotifications = $this->processNotifications($urgentData, $notificationTemplates);
    }

    private function parseRelativeTime($relativeTime)
    {
        $interval = \Carbon\Carbon::now();
        sscanf($relativeTime, '%d %s ago', $amount, $unit);

        // Adjust the interval based on the relative time
        switch ($unit) {
            case 'hour':
            case 'hours':
                $interval->subHours($amount);
                break;
            case 'day':
            case 'days':
                $interval->subDays($amount);
                break;
            case 'minute':
            case 'minutes':
                $interval->subMinutes($amount);
                break;
            default:
                break;
        }

        return $interval->timestamp;
    }

    private function sortNotificationsByTime(&$notifications)
    {
        usort($notifications, function ($a, $b) {
            $timeA = $this->parseRelativeTime($a['time']);
            $timeB = $this->parseRelativeTime($b['time']);

            return $timeB <=> $timeA; // Descending order
        });
    }

    private function processNotifications($notificationsData, $notificationTemplates)
    {
        return array_map(function ($notif) use ($notificationTemplates) {
            return [
                'message' => $this->generateNotification($notificationTemplates[$notif['template']], $notif['data']),
                'label' => $notif['label'],
                'time' => $notif['time'],
                'profile' => $notif['profile'],
            ];
        }, $notificationsData);
    }

    private function generateNotification($template, $data)
    {
        foreach ($data as $key => $value) {
            // Wrap actor and subject in <b> tags for emphasis
            if (in_array($key, ['actor', 'subject'])) {
                $value = "<b>{$value}</b>";
            }
            $template = str_replace('{'.$key.'}', $value, $template);
        }

        return $template;
    }

    public function render()
    {
        return view('livewire.notifications.notifs', [
            'generalNotifications' => $this->generalNotifications,
            'urgentNotifications' => $this->urgentNotifications,
        ]);
    }
}
