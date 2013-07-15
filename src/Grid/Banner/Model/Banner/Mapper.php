<?php

namespace Grid\Banner\Model\Banner;

use Locale;
use Zork\Db\Sql\Expression;
use Zork\Model\Mapper\DbAware\ReadWriteMapperAbstract;
use Zork\Session\ContainerAwareTrait as SessionContainerAwareTrait;

/**
 * Banner mapper
 *
 * @author David Pozsar <david.pozsar@megaweb.hu>
 */
class Mapper extends ReadWriteMapperAbstract
{

    use SessionContainerAwareTrait;

    /**
     * Table name used in all queries
     *
     * @var string
     */
    protected static $tableName = 'banner';

    /**
     * Table name used additinally in select queries
     *
     * @var string
     */
    protected static $propertyTableName = 'banner_property';

    /**
     * Default column-conversion functions as types;
     * used in selected(), deselect()
     *
     * @var array
     */
    protected static $columns = array(
        'id'    => self::INT,
        'type'  => self::STRING,
    );

    /**
     * @var array
     */
    protected static $previousBlockedIds = null;

    /**
     * Service-locator
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Structure factory for the mapper
     *
     * @var \Grid\Banner\Model\Banner\StructureFactory
     */
    protected $structureFactory;

    /**
     * Get service-locator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set service-locator
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Grid\Banner\Model\Banner\Mapper
     */
    public function setServiceLocator( ServiceLocatorInterface $serviceLocator )
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get structure factory
     *
     * @return \Grid\Banner\Model\Banner\StructureFactory
     */
    public function getStructureFactory()
    {
        return $this->structureFactory;
    }

    /**
     * Set structure factory
     *
     * @param \Grid\Banner\Model\Banner\StructureFactory $structurePrototype
     * @return \Grid\Banner\Model\Banner\Mapper
     */
    public function setStructureFactory( $structureFactory )
    {
        $this->structureFactory = $structureFactory;
        return $this;
    }

    /**
     * Contructor: Sets banner structure prototype
     *
     * @param   \Zend\ServiceManager\ServiceLocatorInterface    $serviceLocator
     * @param   \Grid\Banner\Model\Banner\StructureFactory      $bannerStructureFactory
     * @param   \Grid\Banner\Model\Banner\Structure\ProxyBase   $bannerStructurePrototype
     */
    public function __construct( ServiceLocatorInterface    $serviceLocator,
                                 StructureFactory           $bannerStructureFactory,
                                 Structure\ProxyBase        $bannerStructurePrototype = null )
    {
        if ( null === static::$previousBlockedIds )
        {
            $this->releaseBlockedIds();
        }

        parent::__construct( $bannerStructurePrototype ?: new Structure\ProxyBase );

        $this->setServiceLocator( $serviceLocator )
             ->setStructureFactory( $bannerStructureFactory );
    }

    /**
     * @return  array
     */
    protected function getBlockedIds()
    {
        $session = $this->getSessionContainer();

        return array_merge(
            static::$previousBlockedIds,
            $session['blockedIds']
        );
    }

    /**
     * @param   int $id
     * @return  \Grid\Banner\Model\Banner\Mapper
     */
    protected function addToBlockedIds( $id )
    {
        if ( ! empty( $id ) )
        {
            $session = $this->getSessionContainer();
            $session['blockedIds'][] = (int) $id;
        }

        return $this;
    }

    /**
     * Release blocked ids
     *
     * @return \Grid\Banner\Model\Banner\Mapper
     */
    public function releaseBlockedIds()
    {
        $session = $this->getSessionContainer();

        if ( empty( $session['blockedIds'] ) )
        {
            static::$previousBlockedIds = array();
        }
        else
        {
            static::$previousBlockedIds = (array) $session['blockedIds'];
        }

        $session['blockedIds'] = array();
        return $this;
    }

    /**
     * Create structure from plain data
     *
     * @param   array $data
     * @return  \Grid\Banner\Model\Banner\StructureInterface
     */
    protected function createStructure( array $data )
    {
        if ( isset( $data['proxyData'] ) )
        {
            $proxyData = $data['proxyData'] ?: array();
            unset( $data['proxyData'] );
        }
        else
        {
            $proxyData = $data;
        }

        $proxyData['proxyBase'] = parent::createStructure( $data );
        $proxyData['type']      = $proxyData['proxyBase']->type;

        return $this->structureFactory
                    ->factory( $proxyData );
    }

    /**
     * Get select() default columns
     *
     * @return array
     */
    protected function getSelectColumns( $columns = null )
    {
        if ( null === $columns )
        {
            $proxyData = true;
        }
        elseif ( ( $index = array_search( 'proxyData', $columns ) ) )
        {
            $proxyData = true;
            unset( $columns[$index] );
        }
        else
        {
            $proxyData = false;
        }

        $columns = parent::getSelectColumns( $columns );

        if ( $proxyData )
        {
            $platform = $this->getDbAdapter()
                             ->getPlatform();

            $columns['proxyData'] = new Sql\Expression( '(' .
                $this->sql( $this->getTableInSchema(
                        static::$propertyTableName
                     ) )
                     ->select()
                     ->columns( array(
                         new Sql\Expression( 'TEXT( ARRAY_TO_JSON(
                             ARRAY_AGG( ? ORDER BY ? ASC )
                         ) )', array(
                             static::$propertyTableName,
                             'name',
                         ), array(
                             Sql\Expression::TYPE_IDENTIFIER,
                             Sql\Expression::TYPE_IDENTIFIER,
                         ) )
                     ) )
                     ->where( array(
                         new Sql\Predicate\Expression(
                             $platform->quoteIdentifierChain( array(
                                 static::$propertyTableName, 'bannerId'
                             ) ) .
                             ' = ' .
                             $platform->quoteIdentifierChain( array(
                                 static::$tableName, 'id'
                             ) )
                         )
                     ) )
                     ->getSqlString( $platform ) .
            ')' );
        }

        return $columns;
    }

    /**
     * Parse proxy-data
     *
     * Like:
     * <pre>
     * &lt;struct&gt;
     * [{"name":"{key}","value":"{value}"}]
     * &nbsp;...
     * &lt;/struct&gt;
     * </pre>
     *
     * @param string $data
     * @return array
     */
    protected function parseProxyData( & $data )
    {
        if ( empty( $data ) )
        {
            return array();
        }

        $result = array();
        foreach ( json_decode( $data, true ) as $field )
        {
            if ( empty( $field['name'] ) )
            {
                continue;
            }

            $name   = (string) $field['name'];
            $parts  = explode( '.', $name, 2 );
            $value  = isset( $field['value'] ) ? $field['value'] : null;

            if ( count( $parts ) > 1 )
            {
                list( $name, $sub ) = $parts;

                if ( isset( $result[$name] ) )
                {
                    if ( ! is_array( $result[$name] ) )
                    {
                        $result[$name] = (array) $result[$name];
                    }
                }
                else
                {
                    $result[$name] = array();
                }

                $result[$name][$sub] = $value;
            }
            else
            {
                $result[$name] = $value;
            }
        }

        foreach ( $result as & $value )
        {
            if ( is_array( $value ) )
            {
                uksort( $value, 'strnatcmp' );
            }
        }

        return $result;
    }

    /**
     * Transforms the selected data into the structure object
     *
     * @param array $data
     * @return \Zork\Model\Structure\StructureAbstract
     */
    public function selected( array $data )
    {
        if ( isset( $data['proxyData'] ) && is_string( $data['proxyData'] ) )
        {
            $data['proxyData'] = $this->parseProxyData( $data['proxyData'] );
        }

        return parent::selected( $data );
    }

    /**
     * @param   int     $setId
     * @param   string  $locale
     * @param   array   $tagIds
     * @return  \Grid\Banner\Model\Banner\Structure
     */
    public function findRandomBySetIdTagIdsLocale( $setId, $locale, array $tagIds = array() )
    {
        $setId      = (int) $setId;
        $locale     = (string) $locale;
        $language   = Locale::getPrimaryLanguage( $locale );
        $blockedIds = $this->getBlockedIds();

        $banner = $this->findOne( array(
            'id' => new Expression\FunctionCall(
                'banner_random',
                array(
                    $setId,
                    $language,
                    $locale,
                    new Expression\ArrayLiteral(
                        array_map( 'intval', $tagIds )
                    ),
                    new Expression\ArrayLiteral(
                        array_map( 'intval', $blockedIds )
                    ),
                ),
                array(
                    Expression\FunctionCall::TYPE_VALUE,
                    Expression\FunctionCall::TYPE_VALUE,
                    Expression\FunctionCall::TYPE_VALUE,
                    Expression\FunctionCall::TYPE_VALUE,
                    Expression\FunctionCall::TYPE_VALUE,
                )
            )
        ) );

        if ( empty( $banner ) )
        {
            return null;
        }

        $this->addToBlockedIds( $banner->id );
        return $banner;
    }

    /**
     * Save a single property
     *
     * @param   int     $id
     * @param   string  $name
     * @param   mixed   $value
     * @return  int
     */
    private function saveSingleProperty( $id, $name, $value )
    {
        $sql = $this->sql( $this->getTableInSchema(
            static::$propertyTableName
        ) );

        $update = $sql->update()
                      ->set( array(
                          'value'       => $value,
                      ) )
                      ->where( array(
                          'bannerId'    => $id,
                          'name'        => $name,
                      ) );

        $affected = $sql->prepareStatementForSqlObject( $update )
                        ->execute()
                        ->getAffectedRows();

        if ( $affected < 1 )
        {
            $insert = $sql->insert()
                          ->values( array(
                              'bannerId'    => $id,
                              'name'        => $name,
                              'value'       => $value,
                          ) );

            $affected = $sql->prepareStatementForSqlObject( $insert )
                            ->execute()
                            ->getAffectedRows();
        }

        return $affected;
    }

    /**
     * Save a property
     *
     * @param   int     $id
     * @param   string  $name
     * @param   mixed   $value
     * @return  int
     */
    protected function saveProperty( $id, $name, $value )
    {
        $rows   = 0;
        $sql    = $this->sql( $this->getTableInSchema(
            static::$propertyTableName
        ) );

        $like = strtr( $name, array(
            '\\' => '\\\\',
            '%' => '\%',
            '_' => '\_',
        ) ) . '.%';

        if ( is_array( $value ) )
        {
            $nameLikeOrEq = new Sql\Predicate\PredicateSet( array(
                new Sql\Predicate\Like( 'name', $like ),
                new Sql\Predicate\Operator( 'name', Sql\Predicate\Operator::OP_EQ, $name )
            ), Sql\Predicate\PredicateSet::OP_OR );

            if ( empty( $value ) )
            {
                $delete = $sql->delete()
                              ->where( array(
                                  'bannerId'    => $id,
                                  $nameLikeOrEq,
                              ) );

                $rows += $sql->prepareStatementForSqlObject( $delete )
                             ->execute()
                             ->getAffectedRows();
            }
            else
            {
                $keys = array();

                foreach ( $value as $idx => $val )
                {
                    $keys[] = $key = $name . '.' . $idx;
                    $rows += $this->saveSingleProperty( $id, $key, $val );
                }

                $delete = $sql->delete()
                              ->where( array(
                                  'bannerId'    => $id,
                                  $nameLikeOrEq,
                                  new NotIn( 'name', $keys ),
                              ) );

                $rows += $sql->prepareStatementForSqlObject( $delete )
                             ->execute()
                             ->getAffectedRows();
            }
        }
        else
        {
            $rows += $this->saveSingleProperty( $id, $name, $value );

            $delete = $sql->delete()
                          ->where( array(
                              'bannerId'    => $id,
                              new Sql\Predicate\Like( 'name', $like ),
                          ) );

            $rows += $sql->prepareStatementForSqlObject( $delete )
                         ->execute()
                         ->getAffectedRows();
        }

        return $rows;
    }

    /**
     * Save element structure to datasource
     *
     * @param   \Grid\Banner\Model\Banner\Structure\ProxyAbstract $structure
     * @return  int Number of affected rows
     */
    public function save( & $structure )
    {
        if ( ! $structure instanceof Structure\ProxyAbstract ||
             empty( $structure->type ) )
        {
            return 0;
        }

        $data   = ArrayUtils::iteratorToArray( $structure->getBaseIterator() );
        $result = parent::save( $data );

        if ( $result > 0 )
        {
            if ( empty( $structure->id ) )
            {
                $structure->setOption( 'id', $id = $data['id'] );
            }
            else
            {
                $id = $structure->id;
            }

            foreach ( $structure->getPropertiesIterator() as $property => $value )
            {
                $result += $this->saveProperty( $id, $property, $value );
            }
        }

        return $result;
    }

}
