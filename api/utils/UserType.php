<?php
    enum UserType: string {
        case USER = 'USR';
        case VENDOR = 'VNDR';
        case EVENT_ORGANIZER = 'ORG';
        case ADMIN = 'ADMN';
    }