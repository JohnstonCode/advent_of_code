package main

import "testing"

func TestPart1(t *testing.T) {
	input := "Time:      7  15   30\nDistance:  9  40  200"
	result := part1(input)
	if result != "288" {
		t.Errorf("Part1 reult was incorrect, got: %s, expected %s", result, "288")
	}
}

func TestPart2(t *testing.T) {
	input := "Time:      7  15   30\nDistance:  9  40  200"
	result := part2(input)
	if result != "71503" {
		t.Errorf("Part2 reult was incorrect, got: %s, expected %s", result, "71503")
	}
}
