<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Hotels Model
 *
 * @property \App\Model\Table\RoomsTable&\Cake\ORM\Association\HasMany $Rooms
 *
 * @method \App\Model\Entity\Hotel newEmptyEntity()
 * @method \App\Model\Entity\Hotel newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Hotel> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Hotel get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Hotel findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Hotel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Hotel> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Hotel|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Hotel saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Hotel>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Hotel>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Hotel>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Hotel> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Hotel>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Hotel>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Hotel>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Hotel> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HotelsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('hotels');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Rooms', [
            'foreignKey' => 'hotel_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        $validator
            ->scalar('city')
            ->maxLength('city', 255)
            ->requirePresence('city', 'create')
            ->notEmptyString('city');

        $validator
            ->scalar('state')
            ->maxLength('state', 255)
            ->allowEmptyString('state');

        $validator
            ->scalar('country')
            ->maxLength('country', 255)
            ->allowEmptyString('country');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->allowEmptyString('star_rating');

        return $validator;
    }
}
