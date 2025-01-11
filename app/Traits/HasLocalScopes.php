<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\{Builder, Model};

/**
 * @method static Builder scopes(...$scopes)
 * @method Builder scopes(...$scopes)
 */
trait HasLocalScopes
{
	private array $_used_scopes = [];

	protected static function bootHasLocalScopes()
	{
		$scope_buffer = [];

		static::saving(function (Model $model) use (&$scope_buffer) {
			if (isset($model->_used_scopes) && is_array($model->_used_scopes)) {
				foreach ($model->_used_scopes as $scope) {
					$scope_buffer[$scope] = $model->$scope;
					unset($model->$scope);
				}
			}
		});

		static::saved(function (Model $model) use (&$scope_buffer) {
			if (count($scope_buffer) > 0) {
				foreach ($scope_buffer as $scope => $value) {
					$model->setAttribute($scope, $value);
				}
			}
		});
	}

	/**
	 * Apply the scopes to the Eloquent builder instance and return it.
	 *
	 * @param mixed $query
	 * @param mixed $scopes
	 * @return Builder
	 */
	public function scopeScopes($query, $scopes)
	{
		if (count($scopes) === 0) return $query;

		foreach ($scopes as $scope) {
			$query->$scope();
		}

		return $query;
	}

	/**
	 * Load the scopes to the Eloquent model instance and return it.
	 *
	 * @param mixed $scopes
	 * @return $this
	 */
	public function load_scopes(...$scopes)
	{
		if (is_array($scopes[0])) {
			$scopes = $scopes[0];
		}

		$query = static::selectRaw(1)->where($this->getTable() . '.' . $this->getKeyName(), $this->getKey());
		foreach ($scopes as $scope) {
			$query->$scope();
		}

		if ($row = $query->first()) {
			foreach ($scopes as $scope) {
				$this->setAttribute($scope, $row->getAttribute($scope));
				$this->_used_scopes[] = $scope;
			}
		}

		return $this;
	}

	/**
	 * Load the missing scopes to the Eloquent model instance and return it.
	 *
	 * @param mixed $scopes
	 * @return $this
	 */
	public function load_missing_scopes(...$scopes)
	{
		if (is_array($scopes[0])) {
			$scopes = $scopes[0];
		}

		$missing_scopes = [];
		foreach ($scopes as $scope) {
			if (!isset($this->attributes[$scope])) {
				$missing_scopes[] = $scope;
			}
		}

		if (count($missing_scopes) === 0) return $this;

		$query = static::selectRaw(1)->where($this->getTable() . '.' . $this->getKeyName(), $this->getKey());
		foreach ($missing_scopes as $scope) {
			$query->$scope();
		}

		if ($row = $query->first()) {
			foreach ($missing_scopes as $scope) {
				$this->setAttribute($scope, $row->getAttribute($scope));
				$this->_used_scopes[] = $scope;
			}
		}

		return $this;
	}
}
