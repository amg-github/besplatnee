<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Heading extends Model
{

    protected $fillable = [
    	'name', 'parent_id', 'active', 'allias', 'sortindex'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function properties() {
    	return $this->belongsToMany('App\Property');
    }

    public function parent() {
    	return $this->belongsTo('App\Heading', 'parent_id', 'id');
    }

    public function childrens() {
        return $this->hasMany('App\Heading', 'parent_id', 'id');
    }

    public function aliases() {
        return $this->hasMany('App\HeadingAlias');
    }

    public function filters() {
        return $this->hasMany('App\Filter')->orderBy('sortindex');
    }

    public function setAliases($aliases) {
        foreach($aliases as $language => $aliasList) {
            foreach($aliasList as $alias) {
                $this->aliases()->create([
                    'language' => $language,
                    'alias_local' => $alias['local'],
                    'alias_international' => $alias['international'],
                    'property_id' => $alias['property_id'],
                    'property_value' => $alias['property_value'],
                ]);
            }
        }
    }

    //Временный метод. Поменять, когда решится вопрос с получением значения 
    //языковой переменной по языковому коду

    public function getNames() {
        $names = [];

        foreach ($this->aliases as $key => $alias) {
            $name = \Besplatnee::getFromLangFile($alias->language, $this->name);
            $names[$alias->language] = $name;
        }

        return $names;
    }

    /*
     * @var $names массив, где ключ - код языка, значение - название рубрики на этом языке
     */
    public function setNames(array $names) {
        list($group, $key) = explode('.', $this->name);

        $phrases = [];

        foreach($names as $lang => $name) {
            $phrases[$lang] = [$key => $name];
        }

        \Besplatnee::updateLanguageFiles($phrases, $group);
    }

    public function setProperties($properties) {
        $ids = \App\Property::whereIn('name', $properties)->pluck('id');
        $this->properties()->sync($ids);
    }

    public function getLocalAlias() {
        if(!$this->alias_local) { 
            $names = $this->aliases()->where('language', \App::getLocale())->first();

            $this->alias_local = $names ? $names->alias_local : '';
        }

        return $this->alias_local;
    }

    public function getInternationalAlias() {
        if(!$this->alias_international) { 
            $names = $this->aliases()->where('language', \App::getLocale())->first();

            $this->alias_international = $names ? $names->alias_international : '';
        }

        return $this->alias_international;
    }

    public function getUrl($properties = []) {
        return \Besplatnee::headings()->generateUri($this, $properties);
    }
}
