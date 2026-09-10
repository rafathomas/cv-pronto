<?php

namespace App\Domain\Resume\Services;

use App\Domain\Resume\DTOs\EducationData;
use App\Domain\Resume\DTOs\ExperienceData;
use App\Domain\Resume\DTOs\ResumePersonalData;
use App\Domain\Resume\Models\Resume;
use App\Domain\Resume\Models\ResumeCourse;
use App\Domain\Resume\Models\ResumeEducation;
use App\Domain\Resume\Models\ResumeExperience;
use App\Domain\Resume\Models\ResumeLanguage;
use App\Domain\Resume\Models\ResumeSkill;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResumeService
{
    public function updatePersonalData(Resume $resume, ResumePersonalData $data): Resume
    {
        $resume->update($data->toArray());

        return $resume;
    }

    public function updatePhoto(Resume $resume, UploadedFile $file): Resume
    {
        $previousPath = $resume->photo_path;

        $path = $file->storeAs(
            "photos/{$resume->id}",
            Str::uuid().'.'.$file->extension(),
            'resumes',
        );

        $resume->update(['photo_path' => $path]);

        if ($previousPath) {
            Storage::disk('resumes')->delete($previousPath);
        }

        return $resume;
    }

    public function removePhoto(Resume $resume): Resume
    {
        if ($resume->photo_path) {
            Storage::disk('resumes')->delete($resume->photo_path);
            $resume->update(['photo_path' => null]);
        }

        return $resume;
    }

    public function updateProfessionalSummary(Resume $resume, string $summary): Resume
    {
        $resume->update(['professional_summary' => $summary]);

        return $resume;
    }

    public function addExperience(Resume $resume, ExperienceData $data): ResumeExperience
    {
        return $resume->experiences()->create([
            ...$data->toArray(),
            'sort_order' => $resume->experiences()->max('sort_order') + 1,
        ]);
    }

    public function updateExperience(ResumeExperience $experience, ExperienceData $data): ResumeExperience
    {
        $experience->update($data->toArray());

        return $experience;
    }

    public function deleteExperience(ResumeExperience $experience): void
    {
        $experience->delete();
    }

    public function addEducation(Resume $resume, EducationData $data): ResumeEducation
    {
        return $resume->education()->create([
            ...$data->toArray(),
            'sort_order' => $resume->education()->max('sort_order') + 1,
        ]);
    }

    public function updateEducation(ResumeEducation $education, EducationData $data): ResumeEducation
    {
        $education->update($data->toArray());

        return $education;
    }

    public function deleteEducation(ResumeEducation $education): void
    {
        $education->delete();
    }

    public function addCourse(Resume $resume, string $name, ?string $institution = null, ?string $completedAt = null, ?int $workloadHours = null): ResumeCourse
    {
        return $resume->courses()->create([
            'name' => $name,
            'institution' => $institution,
            'completed_at' => $completedAt,
            'workload_hours' => $workloadHours,
            'sort_order' => $resume->courses()->max('sort_order') + 1,
        ]);
    }

    public function deleteCourse(ResumeCourse $course): void
    {
        $course->delete();
    }

    public function addSkill(Resume $resume, string $name): ResumeSkill
    {
        return $resume->skills()->firstOrCreate(
            ['name' => $name],
            ['sort_order' => $resume->skills()->max('sort_order') + 1],
        );
    }

    public function removeSkill(ResumeSkill $skill): void
    {
        $skill->delete();
    }

    public function addLanguage(Resume $resume, string $name, string $level): ResumeLanguage
    {
        return $resume->languages()->create([
            'name' => $name,
            'level' => $level,
            'sort_order' => $resume->languages()->max('sort_order') + 1,
        ]);
    }

    public function removeLanguage(ResumeLanguage $language): void
    {
        $language->delete();
    }
}
