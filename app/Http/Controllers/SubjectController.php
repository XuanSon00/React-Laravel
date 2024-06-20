<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Http\Response;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::all(), 200);
    }

    public function store(Request $request)
    {
        $subject = Subject::create($request->all());
        return response()->json($subject, 201);
    }

    public function show($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }
        return response()->json($subject, 200);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }
        $subject->update($request->all());
        return response()->json($subject, 200);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }
        $subject->delete();
        return response()->json(['message' => 'Subject deleted'], 200);
    }

    public function destroyAll()
    {
        Subject::truncate();
        return response()->json(['message' => 'Subject deleted'], 200);
    }
}
