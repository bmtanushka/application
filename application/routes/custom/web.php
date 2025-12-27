<?php

/**----------------------------------------------------------------------------------------------------------------
 * [GROWCRM - CUSTOM ROUTES]
 * Place your custom routes or overides in this file. This file is not updated with Grow CRM updates
 * ---------------------------------------------------------------------------------------------------------------*/
 
 //EVERYTHING
Route::any('everything', 'Everything@index')->name('everything');

Route::any('pushtoken', 'PushTokenController@saveToken')->name('pushtoken');

Route::any('reaction', 'ReactionController@saveReaction')->name('reaction');