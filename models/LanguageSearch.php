<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    1:30 CH
 * @version 1.0.0
 */
namespace navatech\language\models;

use yii\data\ActiveDataProvider;

class LanguageSearch extends Language {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'id',
					'status',
				],
				'integer',
			],
			[
				[
					'name',
					'code',
					'country',
				],
				'safe',
			],
		];
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 * @since 1.0.0
	 */
	public function search($params) {
		$query        = Language::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['id' => SORT_DESC]],
		]);
		$this->load($params);
		if(!$this->validate()) {
			return $dataProvider;
		}
		$query->andFilterWhere([
			'id'      => $this->id,
			'name'    => $this->name,
			'code'    => $this->code,
			'country' => $this->country,
			'status'  => $this->status,
		]);
		return $dataProvider;
	}
}