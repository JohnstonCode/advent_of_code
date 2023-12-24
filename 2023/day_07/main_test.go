package main

import "testing"

func TestDay7(t *testing.T) {
	t.Run("Part 1", func(t *testing.T) {
		input := "32T3K 765\nT55J5 684\nKK677 28\nKTJJT 220\nQQQJA 483"
		result := part1(input)
		if result != "6440" {
			t.Errorf("Part 1 reult was incorrect, got: %s, expected %s", result, "6440")
		}
	})

	t.Run("Part 2", func(t *testing.T) {
		input := "32T3K 765\nT55J5 684\nKK677 28\nKTJJT 220\nQQQJA 483"
		result := part2(input)
		if result != "5905" {
			t.Errorf("Part 2 reult was incorrect, got: %s, expected %s", result, "5905")
		}
	})
}
