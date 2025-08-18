<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'body'          => $this->body,
            'excerpt'       => $this->excerpt,
            'tags'          => $this->tags,
            'featured_image'=> $this->featured_image,
            'status'        => $this->status,
            'category'      => $this->category,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'published_at'    => $this->published_at,
            'author'        => $this->whenLoaded('author'),
            'comments'      => $this->whenLoaded('comments'),
        ];
    }
}
