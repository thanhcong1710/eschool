<?php

namespace App\Exports;

use App\Imports\FormFieldValuesSheet;
use App\Repositories\FormField\FormFieldsInterface;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class TeacherDataExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithStrictNullComparison, WithMultipleSheets {
    protected mixed $results;
    protected Collection $formFields;

    public function __construct() {
        $formFieldsInterface = app(FormFieldsInterface::class);
        $this->formFields = $formFieldsInterface->all(['name', 'type', 'default_values', 'is_required', 'user_type']);
    }
    
    public function title(): string {
        return 'Teacher Bulk Registration';
    }

    public function headings(): array {
        $columns = [
            'first_name',
            'last_name',
            'mobile',
            'email',
            'gender',
            'dob',
            'qualification',
            'current address',
            'permanent address',
            'salary',
            'joining_date'
        ];
        foreach ($this->formFields as $data) {
            if($data->user_type == 2) {
                if ($data->type != 'file') {
                    $columns[] = str_replace(' ', '_', $data->name);
                }
            }
        }
        return $columns;
    }

    public function sheets(): array {
        $sheets = [];

        // add the main data sheet
        $sheets[] = new TeacherDataExport();

        // add a new sheet for the form field values
        $sheets[] = new FormFieldValuesSheet($this->formFields);

        return $sheets;
    }

    private function getActionItems() {
        $fields = [
            'teacher first name',
            'teacher last name',
            '1234567899',
            'teacher@example.com',
            'male/female',
            date('d-m-Y'),
            'B ed',
            'Norway',
            'Norway',
            '10000',
            date('d-m-Y')
        ];
      
        foreach ($this->formFields as $value) {
            if($value->user_type == 2) {
                switch ($value->type) {
                    case 'text':
                    case 'textarea':
                        $value->is_required == 1 ? array_push($fields, $value->type) : array_push($fields, $value->type . ' OR leave blank');
                        break;
                    case 'number':
                        $value->is_required == 1 ? array_push($fields, "545454") : array_push($fields, "545454 OR leave blank");
                        break;
                    case 'dropdown':
                    case 'radio':
                    case 'checkbox':
                        $value->is_required == 1 ? array_push($fields, '{{ Please Check the Possible Options of it }}') : array_push($fields, '{{ Please Check the Possible Options of it OR leave blank}}');
                        break;
                    default:
                        break;
                }
            }
        }

        return $fields;
    }

    public function collection() {
        // store the results for later use
        $this->results = $this->getActionItems();

        return collect(array($this->results));
    }
}
