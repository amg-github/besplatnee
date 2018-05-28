<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
	public $timestamps = false;

    protected $fillable = [
    	'name', 
    	'title', 
    	'description', 
    	'type',
    	'default',
    	'options',
    ];

    protected $hidden = [
    	'pivot',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function values() {
        return $this->hasMany('App\PropertyDefaultValue');
    }

    public function headings() {
        return $this->belongsToMany('App\Heading')->withPivot('sort');
    }

    public function setValues($values) {
        $this->values()->delete();

        foreach($values as $parent_value => $_values) {
            foreach($_values as $value => $title) {
                if($parent_value == null) {
                    $this->values()->create([
                        'title' => $title,
                        'value' => $value,
                    ]);
                } else {
                    $parentValue = \App\PropertyDefaultValue::where('property_id', $this->parent_id)
                        ->where('value', $parent_value)->first();

                    if($parentValue) {
                        $this->values()->create([
                            'title' => $title,
                            'value' => $value,
                            'parent_value' => $parentValue->id,
                        ]);
                    }
                }
            }
        }

    }

    public function setParent($parent = null, $values = [null => [null]]) {
        $parent = Property::where('name', $parent)->first();

        $this->parent_id = $parent == null ? null : $parent->id;
        $this->save();

        $this->setValues($values);
    }

    public function rules() {
        switch($this->type) {
            case 'numeric':
                    return $this->rulesNumeric();
                break;
            case 'select':
                    return $this->rulesSelect();
                break;
            default:
                return 'nullable';
        }
    }

    public function rulesNumeric() {
        $rules = ['nullable'];

        if(isset($this->options['format'])) {

            if(count($this->options['format']) == 3) {
                $integer = "([1-9][0-9]{0,2})(\\" . $this->options['format'][2] . "?[0-9]{3})*";

                $fraction = $this->options['format'][0] > 0 ? "\\" . $this->options['format'][1] . "[0-9]{" . $this->options['format'][0] . "}" : '';

                $rules[] = "regex:/^" . $integer . $fraction . "|0" . $fraction . '$/';

                if(isset($this->options['min']) && $this->options['min'] !== null) {
                    $rules[] = 'min_format:' . $this->options['min'] . ',"' . $this->options['format'][2] . '","' . $this->options['format'][1] . '"';
                }

                if(isset($this->options['max']) && $this->options['max'] !== null) {
                    $rules[] = 'max_format:' . $this->options['max'] . ',"' . $this->options['format'][2] . '","' . $this->options['format'][1] . '"';
                }
            }

        } else {

            if(isset($this->options['min']) && $this->options['min'] !== null) {
                $rules[] = 'min:' . $this->options['min'];
            }

            if(isset($this->options['max']) && $this->options['max'] !== null) {
                $rules[] = 'max:' . $this->options['max'];
            }

        }

        return $rules;
    }

    public function rulesSelect() {
        $rules = ['nullable'];

        $property_id = $this->id;

        $rules[] = \Illuminate\Validation\Rule::exists((new \App\PropertyDefaultValue)->getTable(), 'value')->where(function ($query) use ($property_id) {
            $query->where('property_id', $property_id);
        });

        return $rules;
    }
}
