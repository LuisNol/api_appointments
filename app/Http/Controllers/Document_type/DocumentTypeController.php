<?php

namespace App\Http\Controllers\Document_type;

use App\Models\DocumentType\DocumentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $this->authorize('viewAny', DocumentType::class);
        $search = $request->search;
        
        $documentTypes = DocumentType::where('name', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json([
            'total' => $documentTypes->total(),
            'document_types' => $documentTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', DocumentType::class);

        // Validar si ya existe un tipo de documento con el mismo nombre
        $documentTypeExists = DocumentType::where('name', $request->name)->first();

        if ($documentTypeExists) {
            return response()->json([
                'message' => 403,
                'message_text' => 'El tipo de documento ya existe',
            ]);
        }

        $documentType = DocumentType::create($request->all());

        return response()->json([
            'message' => 200,
            'document_type' => $documentType,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view', DocumentType::class);
        $documentType = DocumentType::findOrFail($id);

        return response()->json([
            'document_type' => $documentType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update', DocumentType::class);

        $documentType = DocumentType::findOrFail($id);

        // Validar si el nombre del tipo de documento ya está en uso
        $documentTypeExists = DocumentType::where('name', $request->name)
            ->where('id', '<>', $id)
            ->first();

        if ($documentTypeExists) {
            return response()->json([
                'message' => 403,
                'message_text' => 'El tipo de documento ya existe',
            ]);
        }

        $documentType->update($request->all());

        return response()->json([
            'message' => 200,
            'document_type' => $documentType,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', DocumentType::class);

        $documentType = DocumentType::findOrFail($id);
        $documentType->delete();

        return response()->json([
            'message' => 200,
        ]);
    }
}
