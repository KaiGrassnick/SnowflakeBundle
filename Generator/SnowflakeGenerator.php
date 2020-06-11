<?php declare(strict_types=1);
/*******************************************************************************
 * Copyright (c) 2020.
 * Author: Kai Grassnick <info@kai-grassnick.de>
 ******************************************************************************/

namespace KaiGrassnick\Generator;

use Exception;
use Godruoyi\Snowflake\Snowflake;

/**
 * Class SnowflakeGenerator
 *
 * @package KaiGrassnick\Generator
 */
class SnowflakeGenerator
{
    /**
     * @var int
     *
     * @description: static as simple cache to prevent collisions
     */
    static private int $lastTimeStamp = -1;

    /**
     * @var int
     *
     * @description: static as simple cache to prevent collisions
     */
    static private int $sequence = 0;


    /**
     * @return string
     */
    public function generateId(): string
    {
        $snowflake = new Snowflake();
        $snowflake->setSequenceResolver(function ($currentTime) {
            if (self::$lastTimeStamp === $currentTime) {
                self::$sequence++;
                self::$lastTimeStamp = $currentTime;

                return self::$sequence;
            }

            self::$sequence      = 0;
            self::$lastTimeStamp = $currentTime;

            return self::$sequence;
        });

        try {
            $snowflake->setStartTimeStamp(strtotime('2020-05-24') * 1000);
        } catch (Exception $exception) {
            // this will not happen, since this code is static
        }

        return ($snowflake->id());
    }
}
