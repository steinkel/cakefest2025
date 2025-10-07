<?php

/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/*
 * IMPORTANT:
 * This is an example configuration file. Copy this file into your config directory and edit to
 * set up your app permissions.
 *
 * This is a quick roles-permissions implementation
 * Rules are evaluated top-down, first matching rule will apply
 * Each line define
 *      [
 *          'role' => 'role' | ['roles'] | '*'
 *          'prefix' => 'Prefix' | , (default = null)
 *          'plugin' => 'Plugin' | , (default = null)
 *          'controller' => 'Controller' | ['Controllers'] | '*',
 *          'action' => 'action' | ['actions'] | '*',
 *          'allowed' => true | false | callback (default = true)
 *      ]
 * You could use '*' to match anything
 * 'allowed' will be considered true if not defined. It allows a callable to manage complex
 * permissions, like this
 * 'allowed' => function (array $user, $role, Request $request) {}
 *
 * Example, using allowed callable to define permissions only for the owner of the Posts to edit/delete
 *
 * (remember to add the 'uses' at the top of the permissions.php file for Hash, TableRegistry and Request
   [
        'role' => ['user'],
        'controller' => ['Posts'],
        'action' => ['edit', 'delete'],
        'allowed' => function(array $user, $role, Request $request) {
            $postId = Hash::get($request->params, 'pass.0');
            $post = TableRegistry::getTableLocator()->get('Posts')->get($postId);
            $userId = Hash::get($user, 'id');
            if (!empty($post->user_id) && !empty($userId)) {
                return $post->user_id === $userId;
            }
            return false;
        }
    ],
 */

return [
    'CakeDC/Auth.permissions' => [
        //all bypass
        [
            'prefix' => false,
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => [
                // LoginTrait
                'socialLogin',
                'login',
                'logout',
                'socialEmail',
                'verify',
                // RegisterTrait
                'register',
                'validateEmail',
                // PasswordManagementTrait used in RegisterTrait
                'changePassword',
                'resetPassword',
                'requestResetPassword',
                // UserValidationTrait used in PasswordManagementTrait
                'resendTokenValidation',
                'linkSocial',
                //Webauthn2fa actions
                'webauthn2fa',
                'webauthn2faRegister',
                'webauthn2faRegisterOptions',
                'webauthn2faAuthenticate',
                'webauthn2faAuthenticateOptions',
                'requestLoginLink',
                'sendLoginLink',
                'singleTokenLogin',
            ],
            'bypassAuth' => true,
        ],
        [
            'prefix' => false,
            'plugin' => 'CakeDC/Users',
            'controller' => 'SocialAccounts',
            'action' => [
                'validateAccount',
                'resendValidation',
            ],
            'bypassAuth' => true,
        ],
        //admin role allowed to all the things
        [
            'role' => \CakeDC\Users\Model\Table\UsersTable::ROLE_ADMIN,
            'prefix' => '*',
            'extension' => '*',
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',
        ],
        //specific actions allowed for the all roles in Users plugin
        [
            'role' => '*',
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => ['profile', 'logout', 'linkSocial', 'callbackLinkSocial'],
        ],
        [
            'role' => '*',
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => 'resetOneTimePasswordAuthenticator',
            'allowed' => function (array $user, $role, \Cake\Http\ServerRequest $request) {
                $userId = \Cake\Utility\Hash::get($request->getAttribute('params'), 'pass.0');
                if (!empty($userId) && !empty($user)) {
                    return $userId === $user['id'];
                }

                return false;
            }
        ],
        //all roles allowed to Pages/display
        [
            'role' => '*',
            'controller' => 'Pages',
            'action' => 'display',
        ],
        [
            'role' => '*',
            'plugin' => 'DebugKit',
            'controller' => '*',
            'action' => '*',
            'bypassAuth' => true,
        ],
        [
            'role' => '*',
            'prefix' => false,
            'controller' => 'Bookings',
            'action' => '*',
            'bypassAuth' => true,
        ],
        [
            'role' => 'staff',
            'prefix' => 'Admin',
            'controller' => 'Hotels',
            'action' => ['view', 'edit'],
            'allowed' => function (\CakeDC\Users\Model\Entity\User $user, $role, \Cake\Http\ServerRequest $request) {
                $hotelId = (int)$request->getAttribute('params')['pass'][0] ?? 0;
                $myHotelId = (int)$user->hotels_user->hotel_id ?? 0;
                if ($hotelId > 0) {
                    return $hotelId === $myHotelId;
                }

                return false;
            }
        ],
        [
            'role' => 'staff',
            'prefix' => 'Admin',
            'controller' => 'Bookings',
            'action' => ['view', 'edit'],
            'allowed' => function (\CakeDC\Users\Model\Entity\User $user, $role, \Cake\Http\ServerRequest $request) {
                $bookingId = (int)$request->getAttribute('params')['pass'][0] ?? 0;
                $booking = \Cake\ORM\TableRegistry::getTableLocator()->get('Bookings')
                    ->get($bookingId, contain: ['Rooms']);
                $myHotelId = (int)$user->hotels_user->hotel_id ?? 0;
                if ($myHotelId > 0) {
                    return $booking->room->hotel_id === $myHotelId;
                }

                return false;
            }
        ],
    ]
];
