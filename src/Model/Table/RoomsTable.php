<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Rooms Model
 *
 * @property \App\Model\Table\HotelsTable&\Cake\ORM\Association\BelongsTo $Hotels
 * @property \App\Model\Table\RoomTypesTable&\Cake\ORM\Association\BelongsTo $RoomTypes
 * @property \App\Model\Table\BookingsTable&\Cake\ORM\Association\HasMany $Bookings
 *
 * @method \App\Model\Entity\Room newEmptyEntity()
 * @method \App\Model\Entity\Room newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Room> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Room get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Room findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Room patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Room> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Room|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Room saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Room>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Room>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Room>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Room> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Room>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Room>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Room>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Room> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RoomsTable extends Table
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

        $this->setTable('rooms');
        $this->setDisplayField('room_number_display');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hotels', [
            'foreignKey' => 'hotel_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('RoomTypes', [
            'foreignKey' => 'room_type_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Bookings', [
            'foreignKey' => 'room_id',
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
            ->integer('hotel_id')
            ->notEmptyString('hotel_id');

        $validator
            ->integer('room_type_id')
            ->notEmptyString('room_type_id');

        $validator
            ->scalar('room_number')
            ->maxLength('room_number', 10)
            ->requirePresence('room_number', 'create')
            ->notEmptyString('room_number');

        $validator
            ->boolean('is_available')
            ->allowEmptyString('is_available');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['hotel_id'], 'Hotels'), ['errorField' => 'hotel_id']);
        $rules->add($rules->existsIn(['room_type_id'], 'RoomTypes'), ['errorField' => 'room_type_id']);

        return $rules;
    }
}
