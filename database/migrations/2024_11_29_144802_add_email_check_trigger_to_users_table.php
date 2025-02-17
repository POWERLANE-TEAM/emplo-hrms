<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddEmailCheckTriggerToUsersTable extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE OR REPLACE FUNCTION check_email_not_null()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.google_id IS NULL AND NEW.facebook_id IS NULL AND NEW.email IS NULL THEN
                    RAISE EXCEPTION \'Email cannot be null if both google_id and facebook_id are null\';
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER check_email_not_null_trigger
            BEFORE INSERT OR UPDATE ON users
            FOR EACH ROW
            EXECUTE FUNCTION check_email_not_null();
        ');
    }

    public function down()
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS check_email_not_null_trigger ON users;
            DROP FUNCTION IF EXISTS check_email_not_null();
        ');
    }
}
