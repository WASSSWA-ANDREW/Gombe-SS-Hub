<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\StudentDataEntered;
use App\Notifications\StaffDataEntered;
use App\Notifications\AcademicsDataEntered;
use App\Notifications\DisciplineRecordEntered;
use App\Notifications\CounsellingRecordEntered;
use Illuminate\Notifications\Notification;

class NotificationService
{
    /**
     * Get all admin users
     */
    private static function getAdmins()
    {
        return User::whereIn('role', ['admin', 'super_admin'])->get();
    }

    /**
     * Notify all admins of a student data entry
     */
    public static function notifyStudentDataEntered($student, $action = 'created')
    {
        $notification = new StudentDataEntered($student, $action);
        foreach (self::getAdmins() as $admin) {
            $admin->notify($notification);
        }
    }

    /**
     * Notify all admins of a staff data entry
     */
    public static function notifyStaffDataEntered($staff, $action = 'created')
    {
        $notification = new StaffDataEntered($staff, $action);
        foreach (self::getAdmins() as $admin) {
            $admin->notify($notification);
        }
    }

    /**
     * Notify all admins of academics data entry
     */
    public static function notifyAcademicsDataEntered($dataType, $details, $action = 'created')
    {
        $notification = new AcademicsDataEntered($dataType, $details, $action);
        foreach (self::getAdmins() as $admin) {
            $admin->notify($notification);
        }
    }

    /**
     * Notify all admins of discipline record entry
     */
    public static function notifyDisciplineRecordEntered($recordType, $details, $action = 'created')
    {
        $notification = new DisciplineRecordEntered($recordType, $details, $action);
        foreach (self::getAdmins() as $admin) {
            $admin->notify($notification);
        }
    }

    /**
     * Notify all admins of counselling record entry
     */
    public static function notifyCounsellingRecordEntered($recordType, $details, $action = 'created')
    {
        $notification = new CounsellingRecordEntered($recordType, $details, $action);
        foreach (self::getAdmins() as $admin) {
            $admin->notify($notification);
        }
    }
}
