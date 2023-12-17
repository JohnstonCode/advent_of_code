package main

import "testing"

func TestPart1(t *testing.T) {
	input := "467..114..\n...*......\n..35..633.\n......#...\n617*......\n.....+.58.\n..592.....\n......755.\n...$.*....\n.664.598.."
	result := part1(input)
	if result != "4361" {
		t.Errorf("Part1 reult was incorrect, got: %s, expected %s", result, "4361")
	}
}

func TestPart2(t *testing.T) {
	input := "467..114..\n...*......\n..35..633.\n......#...\n617*......\n.....+.58.\n..592.....\n......755.\n...$.*....\n.664.598.."
	result := part2(input)
	if result != "467835" {
		t.Errorf("Part2 reult was incorrect, got: %s, expected %s", result, "467835")
	}
}
