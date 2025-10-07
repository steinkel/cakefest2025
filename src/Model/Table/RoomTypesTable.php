<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RoomTypes Model
 *
 * @property \App\Model\Table\RoomsTable&\Cake\ORM\Association\HasMany $Rooms
 *
 * @method \App\Model\Entity\RoomType newEmptyEntity()
 * @method \App\Model\Entity\RoomType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\RoomType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RoomType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\RoomType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\RoomType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\RoomType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\RoomType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\RoomType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\RoomType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RoomType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RoomType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RoomType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RoomType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RoomType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RoomType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RoomType> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RoomTypesTable extends Table
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

        $this->setTable('room_types');
        $this->setDisplayField('type_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Rooms', [
            'foreignKey' => 'room_type_id',
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
            ->scalar('type_name')
            ->maxLength('type_name', 255)
            ->requirePresence('type_name', 'create')
            ->notEmptyString('type_name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('max_occupancy')
            ->requirePresence('max_occupancy', 'create')
            ->notEmptyString('max_occupancy');

        $validator
            ->decimal('base_price')
            ->requirePresence('base_price', 'create')
            ->notEmptyString('base_price');

        $validator
            ->boolean('pets_allowed')
            ->allowEmptyString('pets_allowed');

        return $validator;
    }
}
