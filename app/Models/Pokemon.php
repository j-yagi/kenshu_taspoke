<?php

/**
 * ポケモンモデル
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/Model.php';
require_once ROOT_DIR . '/lib/danrovito/pokephp/src/PokeApi.php';

class Pokemon extends Model
{
    protected const TABLE = 'pokemons';

    // PokeAPIのポケモンID最小値
    private const MIN_ID = 1;
    // PokeAPIのポケモンID最大値
    private const MAX_ID = 898;

    public ?int $id = null;
    public ?string $name_en = null;
    public ?string $name_ja = null;
    public ?string $front_img_url = null;
    public ?string $back_img_url = null;
    public ?string $img_url = null;
    public ?float $height = null;
    public ?float $weight = null;
    public ?string $type_name = null;
    public ?string $genera_name = null;
    public ?string $flavor_text = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    protected array $fillable = [
        'id',
        'name_en',
        'name_ja',
        'front_img_url',
        'back_img_url',
        'img_url',
        'height',
        'weight',
        'type_name',
        'genera_name',
        'flavor_text',
    ];

    /**
     * ランダムなIDを取得
     *
     * @return int
     */
    public static function getRandomId(): int
    {
        return mt_rand(self::MIN_ID, self::MAX_ID);
    }

    /**
     * プライマリーキーで1件取得
     * 見つからない場合PokeAPIから情報を取得し、DBに登録
     *
     * @param int $id
     * @return self|false
     */
    public static function findOrInsert(int $id)
    {
        $model = self::findOrNew($id);
        if (is_null($model->id)) {
            $api = new PokePHP\PokeApi();

            $pokemon = json_decode($api->pokemon($id));
            if (property_exists($pokemon, 'name')) {
                $model->id = $id;
                $model->name_en = $pokemon->name;
                $model->front_img_url = $pokemon->sprites->front_default;
                $model->back_img_url = $pokemon->sprites->back_default;
                $model->img_url = $pokemon->sprites->other->{"official-artwork"}->front_default;
                $model->height = number_format($pokemon->height / 10, 1);
                $model->weight = number_format($pokemon->weight / 10, 1);

                $type = json_decode($api->pokemonType($pokemon->types[0]->type->name));
                if (property_exists($type, 'names')) {
                    foreach ($type->names as $n) {
                        if ($n->language->name === 'ja_Hrkt' || $n->language->name === 'ja') {
                            $model->type_name = $n->name;
                            break;
                        }
                    }
                }
            }

            $species = json_decode($api->pokemonSpecies($id));
            if (property_exists($species, 'names')) {
                foreach ($species->names as $n) {
                    if ($n->language->name === 'ja_Hrkt' || $n->language->name === 'ja') {
                        $model->name_ja = $n->name;
                        break;
                    }
                }
                foreach ($species->genera as $g) {
                    if ($g->language->name === 'ja_Hrkt' || $g->language->name === 'ja') {
                        $model->type_name = $g->genus;
                        break;
                    }
                }
                foreach ($species->flavor_text_entries as $f) {
                    if ($f->language->name === 'ja_Hrkt' || $f->language->name === 'ja') {
                        $model->flavor_text = $f->flavor_text;
                        break;
                    }
                }
            }

            $model->insert();
        }

        return $model;
    }
}
