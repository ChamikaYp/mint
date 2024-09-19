<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ticket;
use App\Models\Comment;

class TicketView extends Component
{
    use WithFileUploads;

    public $ticket;
    public $ticketId;
    public $newComment;
    // public $imageUpload;
    public $imageUploads = [];

    public function mount($ticketId)
    {
        $this->ticketId = $ticketId;
        $this->ticket = Ticket::with('job', 'comments.user')->findOrFail($ticketId); // Load ticket with job and comments
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required',
            'imageUploads.*' => 'nullable|image|max:1024', // Validate each image
        ]);

        
        $comment = Comment::create([
            'body' => $this->newComment,
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
        ]);
        
        if ($this->imageUploads) {
            foreach ($this->imageUploads as $image) {
                $imagePath = $image->store('comments', 'public');
                // Store each image path in the comment (you may want to create a separate CommentImage model)
                $comment->images()->create(['path' => $imagePath]); // Assuming a CommentImage model
            }
        }

        $this->newComment = '';
        $this->imageUploads = [];

        $this->ticket = Ticket::with('comments.user', 'job')->findOrFail($this->ticket->id); // Refresh comments
    }

    public function render()
    {
        return view('livewire.ticket-view', [
            'ticket' => $this->ticket,
            'comments' => $this->ticket->comments,
        ]);
    }

}
