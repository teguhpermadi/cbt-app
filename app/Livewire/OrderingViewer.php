<?php

namespace App\Livewire;

use Livewire\Component;

class OrderingViewer extends Component
{
    public $options = [];
    public $correctOrder = [];
    public $showCorrectAnswers = false;
    
    public function mount($options, $correctAnswers, $showCorrectAnswers = true)
    {
        $this->options = $options ?? [];
        $this->showCorrectAnswers = $showCorrectAnswers;
        
        // Parse correct answers from different formats
        $this->correctOrder = $this->parseCorrectAnswers($correctAnswers);
    }
    
    /**
     * Parse correct answers from different formats
     */
    private function parseCorrectAnswers($correctAnswers)
    {
        if (empty($correctAnswers)) {
            return [];
        }
        
        // Handle string JSON format
        if (is_string($correctAnswers)) {
            $decoded = json_decode($correctAnswers, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $correctAnswers = $decoded;
            }
        }
        
        // Handle array format
        if (is_array($correctAnswers)) {
            // If it's a single structured array with 'order' key
            if (isset($correctAnswers['order'])) {
                return $correctAnswers['order'];
            }
            
            // If it's already a simple array of keys
            if (count($correctAnswers) > 0 && !isset($correctAnswers[0])) {
                return array_keys($correctAnswers);
            }
            
            // If it's an array with one element containing the order
            if (count($correctAnswers) === 1 && is_array($correctAnswers[0])) {
                $first = $correctAnswers[0];
                if (isset($first['order'])) {
                    return $first['order'];
                }
            }
        }
        
        return [];
    }
    
    /**
     * Get the correct order number for an option key
     */
    public function getCorrectOrderNumber($optionKey)
    {
        $order = array_search($optionKey, $this->correctOrder);
        return $order !== false ? $order + 1 : null;
    }
    
    /**
     * Check if an option is in the correct position
     */
    public function isCorrectPosition($optionKey, $position)
    {
        $correctOrderNumber = $this->getCorrectOrderNumber($optionKey);
        return $correctOrderNumber === $position;
    }
    
    /**
     * Get option text
     */
    public function getOptionText($optionKey)
    {
        if (!isset($this->options[$optionKey])) {
            return '';
        }
        
        $option = $this->options[$optionKey];
        
        if (is_string($option)) {
            return $option;
        }
        
        if (is_array($option)) {
            return $option['text'] ?? '';
        }
        
        return '';
    }
    
    /**
     * Get option media URL
     */
    public function getOptionMediaUrl($optionKey)
    {
        if (!isset($this->options[$optionKey])) {
            return null;
        }
        
        $option = $this->options[$optionKey];
        
        if (is_array($option) && isset($option['media_id'])) {
            return $this->getMediaUrl($option['media_id']);
        }
        
        return null;
    }
    
    /**
     * Get media URL from media ID
     */
    private function getMediaUrl($mediaId)
    {
        if (!$mediaId) {
            return null;
        }
        
        try {
            // This would need to be implemented based on your media storage system
            // For now, return a placeholder or null
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Get sorted options in correct order
     */
    public function getSortedOptions()
    {
        if (empty($this->correctOrder)) {
            return $this->options;
        }
        
        $sorted = [];
        foreach ($this->correctOrder as $key) {
            if (isset($this->options[$key])) {
                $sorted[$key] = $this->options[$key];
            }
        }
        
        return $sorted;
    }
    
    public function render()
    {
        return view('livewire.ordering-viewer');
    }
}
