<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\CandidateEvaluation;
use App\Models\Interview;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        // Candidato 1 - Laura Sánchez (Person 11) - Prospecto por entrevistar
        $laura = Candidate::create([
            'person_id' => 11,
            'job_vacancy_id' => 1,
            'source' => 'portal',
            'application_date' => '2024-01-10',
            'evaluation_score' => 85.00,
            'status' => 'prospect_interview',
            'notes' => 'Candidata con buena actitud y experiencia en retail.',
            'created_by' => 1,
        ]);

        CandidateDocument::create([
            'candidate_id' => $laura->id,
            'document_type' => 'ine',
            'document_name' => 'INE_Laura_Sanchez.pdf',
            'file_path' => 'candidates/ine_laura_sanchez.pdf',
            'is_verified' => true,
        ]);

        CandidateEvaluation::create([
            'candidate_id' => $laura->id,
            'evaluation_type' => 'initial',
            'score' => 85,
            'max_score' => 100,
            'percentage' => 85,
            'passed' => true,
            'evaluated_by' => 1,
            'notes' => 'Buena evaluación inicial. Conocimientos básicos de atención al cliente.',
        ]);

        Interview::create([
            'candidate_id' => $laura->id,
            'interview_type' => 'initial',
            'scheduled_at' => now()->addDays(3),
            'interviewer_id' => 5,
            'status' => 'scheduled',
            'notes' => 'Entrevista inicial con supervisora de atención.',
            'created_by' => 1,
        ]);

        // Candidato 2 - Roberto Medina (Person 12) - Evaluación pendiente
        $roberto = Candidate::create([
            'person_id' => 12,
            'job_vacancy_id' => 2,
            'source' => 'referral',
            'application_date' => '2024-01-15',
            'status' => 'candidate_eval_pending',
            'notes' => 'Recomendado por empleado actual. Pendiente de documentos.',
            'created_by' => 1,
        ]);

        // Candidato 3 - Natalia Pérez (Person 13) - A prueba en tienda
        $natalia = Candidate::create([
            'person_id' => 13,
            'job_vacancy_id' => 3,
            'source' => 'portal',
            'application_date' => '2024-01-05',
            'evaluation_score' => 90.00,
            'trial_start_date' => '2024-01-20',
            'trial_end_date' => '2024-02-20',
            'trial_evaluation' => 88.00,
            'status' => 'trial_store',
            'notes' => 'En periodo de prueba en tienda. Desempeño satisfactorio.',
            'created_by' => 1,
        ]);

        CandidateDocument::create([
            'candidate_id' => $natalia->id,
            'document_type' => 'ine',
            'document_name' => 'INE_Natalia_Perez.pdf',
            'file_path' => 'candidates/ine_natalia_perez.pdf',
            'is_verified' => true,
        ]);

        CandidateDocument::create([
            'candidate_id' => $natalia->id,
            'document_type' => 'curp',
            'document_name' => 'CURP_Natalia_Perez.pdf',
            'file_path' => 'candidates/curp_natalia_perez.pdf',
            'is_verified' => true,
        ]);

        CandidateEvaluation::create([
            'candidate_id' => $natalia->id,
            'evaluation_type' => 'initial',
            'score' => 90,
            'max_score' => 100,
            'percentage' => 90,
            'passed' => true,
            'evaluated_by' => 1,
            'notes' => 'Excelente evaluación inicial. Muy motivada.',
        ]);

        Interview::create([
            'candidate_id' => $natalia->id,
            'interview_type' => 'final',
            'scheduled_at' => now()->subDays(5),
            'completed_at' => now()->subDays(5),
            'interviewer_id' => 5,
            'status' => 'completed',
            'score' => 88,
            'recommendation' => 'approve',
            'notes' => 'Entrevista final completada. Recomendada para contratación.',
            'created_by' => 1,
        ]);
    }
}
