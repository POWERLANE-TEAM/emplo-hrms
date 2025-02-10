<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {

            $this->call([
                PsgcSeeder::class,
                UserStatusSeeder::class,
                ApplicationStatusSeeder::class,
                RolesAndPermissionsSeeder::class,
                ShiftSeeder::class,
                EmploymentStatusSeeder::class,
                DepartmentSeeder::class,
                JobLevelSeeder::class,
                SpecificAreaSeeder::class,
                JobFamilySeeder::class,
                JobTitleSeeder::class,
                BasicUserSeeder::class,
                IntermediateUserSeeder::class,
                AdvancedUserSeeder::class,
                JobVacancySeeder::class,
                PreempRequirementSeeder::class,
                PerformanceCategorySeeder::class,
                PerformanceRatingSeeder::class,
                HolidaySeeder::class,
                LeaveCategorySeeder::class,
                InterviewParameterSeeder::class,
                InterviewRatingSeeder::class,
                ResignationStatusSeeder::class,
                // AttendanceLogSeeder::class,
                UserSeeder::class,
                // ApplicantSeeder::class,
                // NewApplicantSeeder::class,
                QualifiedApplicantSeeder::class,
                // ApplicationSeeder::class,
                ApplicantEducationSeeder::class,
                ApplicantExperienceSeeder::class,
                ApplicantSkillSeeder::class,
                JobEducationKeywordSeeder::class,
                JobExperienceKeywordSeeder::class,
                JobSkillKeywordSeeder::class,
                ManagerialSeeder::class,
                PayrollSeeder::class,
                OvertimeSeeder::class,
                EmployeeShiftSeeder::class,
                EmployeeJobDetailSeeder::class,
                // EmployeeLeaveSeeder::class,
                IssueTypeSeeder::class,
                IssueSeeder::class,
                IncidentSeeder::class,
                HrStaffSeeder::class,
                HrManagerSeeder::class,
                OperationsEmployeesSeeder::class,
                AccountingEmployeesSeeder::class,
                AdministrativeEmployeesSeeder::class,
                GASupportEmployeesSeeder::class,
                GAEmployeesSeeder::class,
                PayrollEmployeesSeeder::class,
                JobFamilyOfficerSeeder::class,
                ProbationaryEmployeesSeeder::class,
                EmployeeLifecycleSeeder::class,
                ThirdMonthEvaluationSeeder::class,
                FifthMonthEvaluationSeeder::class,
                FinalMonthEvaluationSeeder::class,
                TrainingProviderSeeder::class,
                TrainingSeeder::class,
                SeparatedEmployeeSeeder::class,
                ServiceIncentiveLeaveCreditSeeder::class,
                AnnouncementSeeder::class,
                RegularPerformanceEvaluation2024Seeder::class,
                ProbationaryPerformanceEvaluation2024Seeder::class,
                Issue2024Seeder::class,
                AttendanceLog2024Seeder::class,
                PayrollSummarySeeder::class,

                EmployeeSeeder::class,
                // GuestSeeder::class,
                // RegularPerformanceSeeder::class,
                // AttendanceSeeder::class
            ]);
        });
    }
}
