<?php
namespace App\Imports;
use App\Models\Marks;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MarksImport implements ToCollection,WithHeadingRow
{
    
    private $course_id;
    private $batch_id;
    private $dept_id;
    private $program_id;
    
    public function setParameter($course_id,$batch_id,$dept_id,$program_id)
    {
        $this->course_id = $course_id;
        $this->batch_id = $batch_id;
        $this->dept_id = $dept_id;
        $this->program_id = $program_id;
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $mark = new Marks();
            
            $student = Student::where('reg_no', $row['registration_no'])->first();
            if(!is_null($student))
            {
                
                    Marks::updateOrCreate(
                    [
                        'student_id' => $student->id, 'course_id' => $this->course_id
                    ],
                    [
                        'student_id'     => $student->id,
                        'marks' => $row['marks'],
                        'attendance'    => $row['attendance'],
                        'course_id'  => $this->course_id,
                        'batch_id'  => $this->batch_id,
                        'dept_id'  => $this->dept_id,
                        'program_id'  => $this->program_id
                    ]
                );
                
                
            }
            
        }
    }
}