<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class C_event extends Controller
{
    //

    //
    public function event(Event $event)
    {
        try {
            // Mendapatkan semua user
            $events = $event->all();
            return response()->json($events);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create(Request $request)
    {
        $validation = $this->validator($request->all());
        // Melakukan validasi request
        if ($validation->fails()) {
            return response()->json($validation->errors());
        }
        try {
            $event = new Event;
            $event->title        = $request['title'];
            $event->cover        = $request['cover'];
            $event->user_id      = $request['user_id'];
            $event->is_published = $request['is_published'];
            $event->published_at = NOW();
            $event->seen         = 0;
            $event->slug         = $this->sluggenerator($request['slug']);
            $event->content      = $request['content'];
            // Menyimpan data
            $event->save();
            return response()->json([
                'message'   => 'success',
                'event'      => $event
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function detail($event_id)
    {
        try {
            $event = Event::find($event_id);
            return response()->json([
                'message' => 'success',
                'data_event' => $event,
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request)
    {
        try {
            $event = Event::find($request->id);
            $event->update([
                'title'        => $request->title,
                'cover'        => $request->cover,
                'user_id'      => $request->user_id,
                'is_published' => $request->is_published,
                'published_at' => NOW(),
                'seen'         => 0,
                'slug'         => $this->sluggenerator($request->slug),
                'content'      => $request->content,
            ]);
            return response()->json([
                'message' => 'success',
                'data_event' => $event,
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $validation = $this->validator($request->all());
        // Melakukan validasi request
        if ($validation->fails()) {
            return response()->json($validation->errors());
        }
    }

    public function delete($event_id)
    {
        try {
            $event = Event::find($event_id)->delete();
            return response()->json([
                'message' => 'success',
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function validator($data)
    {
        // Melakukan validasi request
        return Validator::make($data, [
            'title'      => 'required|string',
            'slug'      => 'required|string',
            'content'      => 'required|string',
        ]);
    }
    public function sluggenerator($string)
    {
        $slug = trim($string); // trim the string
        $slug = preg_replace('/[^a-zA-Z0-9 -]/', '', $slug); // only take alphanumerical characters, but keep the spaces and dashes too...
        $slug = str_replace(' ', '-', $slug); // replace spaces by dashes
        $slug = strtolower($slug);  // make it lowercase
        return $slug;
    }
}
